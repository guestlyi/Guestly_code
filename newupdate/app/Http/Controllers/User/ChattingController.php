<?php

namespace App\Http\Controllers\User;

use App\Events\UpdateUserMessage;
use App\Events\UserMessage;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Chat;
use App\Models\Package;
use App\Models\Property;
use App\Models\User;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChattingController extends Controller
{
    use Upload, Notify;

    public function view(Request $request)
    {
        try {
            $user = Auth::user();

            $allChat = Chat::with(['reply', 'sender', 'receiver','property'])
                ->withCount('reply')
                ->where(function ($query) use ($user) {
                    $query->where('receiver_id', $user->id)
                        ->orWhere('sender_id', $user->id);
                })
                ->whereNull('chat_id')
                ->orderBy('created_at', 'DESC')
                ->get();

            $chat = null;
            if ($request->has('id')) {
                $chat = Chat::where('id', $request->id)
                    ->latest()
                    ->with('reply', 'sender', 'receiver')
                    ->firstOr(function () {
                        throw new \Exception('Chat not found.');
                    });
            }

            return view(template() . 'user.chats.view', compact('allChat', 'chat'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reply(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,png,pdf|max:2048',
        ], [
            'attachments.*.mimes' => 'Each attachment must be a file of type: jpg, jpeg, png.',
            'attachments.*.max' => 'Each attachment may not be greater than 2MB.',
        ]);

        if (empty($request->input('message')) && !$request->hasFile('attachments')) {
            return back()->with('error', 'Either the message or at least one attachment is required.');
        }

        try {
            $product = Property::with('host:id')->where('id', $request->property_id)->orWhere('slug', $request->slug)->select('id', 'host_id','slug')->firstOr(function () {
                throw new \Exception('Property not found.');
            });

            if (isset($request->chat)) {
                $OldChat = Chat::where('id', $request->chat)->first();
            }

            if ($product->host_id == auth()->id()){
                $receiver = $OldChat->sender_id;
            } else{
                $receiver = $product->host_id;
            }

            $chat = new Chat();

            if ($request->hasFile('attachments')) {
                $path = [];
                $driver = '';

                foreach ($request->attachments as $img) {
                    $image = $this->fileUpload($img, config('filelocation.chat.path'), null, null, 'webp', 80);
                    $path[] = $image['path'];

                    if (!empty($image['driver'])) {
                        $driver = $image['driver'];
                    }
                }

                $chat->attachment = json_encode($path);
                $chat->driver = $driver;
                $chat->last_reply = now();
                $chat->save();
            }

            $chat->message = $request->message;
            $chat->user_id = !$request->chat ? Auth::user()->id : $OldChat->user_id;
            $chat->chat_id = $request->chat ??  null;
            $chat->property_id = $product->id;
            $chat->sender_id = Auth::user()->id;
            $chat->receiver_id = $receiver;
            $chat->last_reply = now();
            $chat->save();
            $chat->link = route('user.chat.list') . '?id=' . $request->chat  ?? $chat->id;
            $chat->save();

            $receiverUser = User::where('id', $chat->receiver_id)->first();

            $params = [
                'user' => auth()->user()->firstname . ' ' . auth()->user()->lastname,
                'message' => Str::limit($chat->message, 20),
            ];


            $action = [
                "link" => $chat->link,
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($receiverUser, 'MASSAGE_SENT', $params);
            $this->userPushNotification($receiverUser, 'MASSAGE_SENT', $params, $action);
            $this->userFirebasePushNotification($receiverUser, 'MASSAGE_SENT', $params);

            return back()->with('success', 'Message Send.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function searchData(Request $request)
    {
        $value = $request->input('search');

        $chats = Chat::query()
            ->with('sender')
            ->whereNull('chat_id')
            ->when($value, function ($query, $value) {
                $query->whereHas('sender', function ($query) use ($value) {
                    $query->where('firstname', 'like', '%' . $value . '%')
                        ->orWhere('lastname', 'like', '%' . $value . '%');
                });
            })
            ->get();

        foreach ($chats as $chat) {
            $chat->url = route('user.chat.list', ['id' => $chat->id]);
            $chat->image = getFile($chat->sender->image_driver, $chat->sender->image);
        }

        return $chats;
    }

    public function delete($id)
    {
        try {
            $chat = Chat::where('id', $id)->with('reply')->firstOr(function () {
                throw new \Exception('Chat not found.');
            });

            if (!empty($chat->reply)) {
                foreach ($chat->reply as $item) {
                    $item->delete();
                    if ($item->attachment) {
                        $driver = $item->driver;
                        $attachments = json_decode($item->attachment);

                        if (is_array($attachments)) {
                            foreach ($attachments as $value) {
                                $this->fileDelete($driver, $value);
                            }
                        } elseif (is_string($attachments)) {
                            $this->fileDelete($driver, $attachments);
                        }
                    }
                }
            }

            $chat->delete();

            return back()->with('success', 'Chat Deleted Successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function nickname(Request $request, $id)
    {
        try {
            $chat = Chat::where('id', $id)->where('chat_id', '=', null)->firstOr(function () {
                throw new \Exception('Chat not found.');
            });

            $chat->nickname = $request->nickname;
            $chat->save();

            return back()->with('success', 'Nickname Setup Successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function chatDetails(Request $request)
    {
        $chatId = $request->chat_id;

        $messages = Chat::where('chat_id', $chatId)
            ->orderBy('created_at')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'user' => $msg->sender?->name ?? 'Unknown',
                    'message' => $msg->message,
                    'attachment' => $msg->attachment ? asset('uploads/chat/' . $msg->attachment) : null,
                    'is_sender' => $msg->sender_id === auth()->id(),
                ];
            });

        return response()->json(['messages' => $messages]);
    }


    public function filter(Request $request)
    {
        $type = $request->get('type', 'all');
        $authId = auth()->id();

        $chatList = Chat::with(['sender', 'receiver'])
            ->whereNull('chat_id')
            ->where(function ($q) use ($authId) {
                $q->where('user_id', $authId)
                    ->orWhere('sender_id', $authId)
                    ->orWhere('receiver_id', $authId);
            })
            ->when($type === 'unread', function ($query) {
                $query->where('seen', 0);
            })
            ->orderByDesc('id')
            ->get()
            ->unique('booking_uid')
            ->values();

        $html = view(template().'user.chats.partials.chatList', compact('chatList'))->render();

        return response()->json([
            'html' => $html
        ]);
    }
    public function newChat(Request $request){
        $validated = $request->validate([
            'message' => 'required|string',
            'property_slug' => 'nullable|string|exists:properties,slug',
            'booking_uid' => 'nullable|string|exists:bookings,uid',
        ]);

//        try {
            $property = null;
            $booking = null;

            if (isset($request->property_slug)){
                $property = Property::where('slug', $request->property_slug)->firstOr(function () {
                    throw new \Exception('Property not found.');
                });
            }
            if (isset($request->booking_uid)){
                $booking = Booking::where('uid', $request->booking_uid)->firstOr(function () {
                    throw new \Exception('Booking not found.');
                });
            }

            $oldChat = Chat::where('user_id', auth()->id())
                ->where(function ($query) use ($booking, $property) {
                    if ($booking) {
                        $query->where('booking_uid', $booking->uid);
                    }
                    if ($property) {
                        $query->orWhere('property_id', $property->id);
                    }
                })
                ->first();

            if ($oldChat) {
                $chat = new Chat();
                $chat->chat_id = $oldChat->id;
                $chat->user_id = $oldChat->user_id;
                $chat->booking_uid = $oldChat->booking_uid ?? null;
                $chat->property_id = $oldChat->property_id;
                $chat->sender_id = auth()->id();
                $chat->receiver_id = $booking->guest_id ?? $property->host_id ?? null;
                $chat->message = $validated['message'];
                $chat->save();

                return back()->with('success', 'Chat Updated Successfully.');
            }

            $chat = new Chat();
            $chat->user_id = auth()->id();
            $chat->booking_uid = $booking->uid ?? null;
            $chat->property_id = $property->id ?? $booking->property_id ?? null;
            $chat->sender_id = auth()->id();
            if ($booking === null) {
                $chat->receiver_id = $property?->host_id;
            } else {
                if (auth()->id() == $booking->guest_id) {
                    $chat->receiver_id = $property?->host_id ?? $booking->property?->host_id;
                } else {
                    $chat->receiver_id = $booking->guest_id;
                }
            }
            $chat->message = $validated['message'];
            $chat->save();

            return back()->with('success', 'Chat Created Successfully.');
//        }catch (\Exception $e){
//            return back()->with('error', $e->getMessage());
//        }
    }
}
