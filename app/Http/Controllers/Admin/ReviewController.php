<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ReviewController extends Controller
{
    public function list(Request $request)
    {
        $data['reviews'] = collect(Review::selectRaw('COUNT(id) AS totalReview')
            ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS activeReview')
            ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END) / COUNT(id)) * 100 AS activeReviewPercentage')
            ->selectRaw('COUNT(CASE WHEN status = 0 THEN id END) AS inActiveReview')
            ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END) / COUNT(id)) * 100 AS inActiveReviewPercentage')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURRENT_DATE THEN id END) AS todayReview')
            ->selectRaw('(COUNT(CASE WHEN DATE(created_at) = CURRENT_DATE THEN id END) / COUNT(id)) * 100 AS todayReviewPercentage')
            ->selectRaw('COUNT(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN id END) AS thisMonthReview')
            ->selectRaw('(COUNT(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN id END) / COUNT(id)) * 100 AS thisMonthReviewPercentage')
            ->get()
            ->toArray())->collapse();


        $data['reviews']['activeReviewPercentage'] = number_format($data['reviews']['activeReviewPercentage'], 0);
        $data['reviews']['inActiveReviewPercentage'] = number_format($data['reviews']['inActiveReviewPercentage'], 0);
        $data['reviews']['todayReviewPercentage'] = number_format($data['reviews']['todayReviewPercentage'], 0);
        $data['reviews']['thisMonthReviewPercentage'] = number_format($data['reviews']['thisMonthReviewPercentage'], 0);

        $data['productId'] = $request->property_id ?? null;

        return view('admin.review', $data);
    }

    public function search(Request $request)
    {
        $search = $request->search['value'] ?? null;
        $filterName = $request->name;
        $productId = $request->productId;
        $filterStatus = $request->filterStatus;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $cards = Review::with(['guest:id,firstname,lastname,username,image,image_driver', 'property.photos','replies.guest:id,firstname,lastname,username,image,image_driver'])
            ->has('property')
            ->has('guest')
            ->whereNull('review_id')
            ->latest()
            ->when(isset($filterName), function ($query) use ($filterName) {
                return $query->whereHas('property', function ($reviewQuery) use ($filterName) {
                    $reviewQuery->where('title', 'LIKE', '%' . $filterName . '%');
                });
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus != "all") {
                    return $query->where('status', $filterStatus);
                }
            })
            ->when(isset($productId), function ($query) use ($productId) {
                $query->whereHas('property', function ($reviewQuery) use ($productId) {
                    $reviewQuery->where('id', $productId);
                });
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('comment', 'LIKE', "%{$search}%")
                        ->orWhereHas('property', function ($reviewQuery) use ($search) {
                            $reviewQuery->where('title', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('guest', function ($reviewerQuery) use ($search) {
                            $reviewerQuery->where('firstname', 'LIKE', "%{$search}%")
                                ->orWhere('lastname', 'LIKE', "%{$search}%");
                        });
                });
            });

        return DataTables::of($cards)
            ->addColumn('checkbox', function ($item) {
                return '<input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';

            })
            ->addColumn('property', function ($item) {
                $url = route('admin.property.edit', $item->property_id);
                $img = getFile($item->property->photos->images['thumb']['driver'], $item->property->photos->images['thumb']['path']);
                $formatedTitle = Str::limit($item->property->title, 20, '...');
                return '<a class="d-flex align-items-center" href="' . $url . '">
                    <div class="avatar">
                      <img class="avatar-img" src="' . $img . '" alt="Image Description">
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <span class="card-title h5 text-dark text-inherit">' . $formatedTitle . '</span>
                    </div>
                  </a>
                 ';
            })
            ->addColumn('reviewer', function ($item) {
                $url = route('admin.user.view.profile', $item->guest_id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                  ' . $item->guest?->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . $item->guest?->firstname . ' ' . $item->guest?->lastname . '</h5>
                                  <span class="fs-6 text-body">' . $item->guest?->username . '</span>
                                </div>
                              </a>';

            })
            ->addColumn('review', function ($item) {
                $star = asset('assets/admin/img/star.svg');

                $ratingValues = (array) $item->rating;
                $averageRating = array_sum($ratingValues) / count($ratingValues);

                $starRating = '';
                for ($i = 0; $i < round($averageRating); $i++) {
                    $starRating .= '<img src="' . $star . '" alt="Review rating" width="14">';
                }

                $fullComment = htmlspecialchars($item->comment);
                $shortComment = mb_substr($item->comment, 0, 20) . (mb_strlen($item->comment) > 20 ? '...' : '');

                $uniqueId = 'comment_' . $item->id;

                return '
                    <div class="text-wrap" style="width: 18rem;">
                        <div class="d-flex gap-1 mb-2">' . $starRating . '</div>
                        <p id="' . $uniqueId . '_short">' . $shortComment .
                                    (mb_strlen($item->comment) > 20 ? ' <a href="javascript:void(0)" onclick="toggleComment(\'' . $uniqueId . '\')">See more</a>' : '') .
                                    '</p>
                        <p id="' . $uniqueId . '_full" style="display:none;">' . nl2br($fullComment) . ' <a href="javascript:void(0)" onclick="toggleComment(\'' . $uniqueId . '\')">See less</a></p>
                    </div>';
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at, basicControl()->date_time_format);
            })

            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Publish') . '
                  </span>';

                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('Hold') . '
                  </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $item->replies->map(function ($reply) {
                    $reply->guest->imageUrl = getFile($reply->guest->image_driver, $reply->guest->image);
                    $reply->toggleUrl = route('admin.review.toggle.status', $reply->id);
                });

                $replies = htmlspecialchars($item->replies, ENT_QUOTES, 'UTF-8');
                $buttonText = trans('Replies');

                return <<<HTML
                    <div class="btn-group" role="group">
                        <a href="#"
                           class="btn btn-white btn-sm repliese_btn"
                           data-replies="{$replies}"
                           data-bs-toggle="modal"
                           data-bs-target="#repliesModal"
                        >
                            <i class="bi-chat-dots me-1"></i> {$buttonText}
                        </a>
                    </div>
                    HTML;
            })
            ->rawColumns(['checkbox', 'property', 'reviewer', 'review', 'date', 'status','action'])
            ->make(true);
    }

    public function multipleDelete(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select row.');
            return response()->json(['error' => 1]);
        } else {
            Review::whereIn('id', $request->strIds)->get()->map(function ($query) {
                $query->delete();
                return $query;
            });
            session()->flash('success', 'Review has been deleted successfully');
            return response()->json(['success' => 1]);
        }
    }

    public function multipleStatusChange(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select row.');
            return response()->json(['error' => 1]);
        } else {
            Review::whereIn('id', $request->strIds)->get()->map(function ($query) {

                $query->status = ($query->status == 1) ? 0 : 1;
                $query->save();

                return $query;
            });
            session()->flash('success', 'Review has been changed successfully');
            return response()->json(['success' => 1]);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $reply = Review::findOrFail($id);

            $reply->status = $reply->status == 1 ? 0 : 1;
            $reply->save();

            return response()->json([
                'success' => true,
                'status'  => $reply->status,
                'message' => $reply->status == 1
                    ? 'Reply activated successfully.'
                    : 'Reply deactivated successfully.'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reply not found.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}
