<?php

namespace App\Services;

use App\Models\ContentDetails;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\DB;

class ContentTranslationService
{
    /**
     * Translate content from one language to another
     *
     * @param int $sourceLangId
     * @param int $targetLangId
     * @param string $sourceCode  ISO language code (e.g. 'en')
     * @param string $targetCode  ISO language code (e.g. 'es')
     * @return void
     */
    public function translateContent(int $sourceLangId, int $targetLangId, string $sourceCode, string $targetCode): void
    {
        $contents = ContentDetails::where('language_id', $sourceLangId)->get();

        $translator = new GoogleTranslate();
        $translator->setSource($sourceCode);
        $translator->setTarget($targetCode);

        foreach ($contents as $item) {
            $translatedDetails = [];

            foreach ((array) $item->description as $key => $value) {
                $translatedDetails[$key] = $translator->translate($value);
            }

            $descriptionValue = empty($translatedDetails) ? null : json_encode($translatedDetails);

            $exists = DB::table('content_details')
                ->where('content_id', $item->content_id)
                ->where('language_id', $targetLangId)
                ->exists();

            if ($exists) {
                DB::table('content_details')
                    ->where('content_id', $item->content_id)
                    ->where('language_id', $targetLangId)
                    ->update(['description' => $descriptionValue]);
            } else {
                DB::table('content_details')
                    ->insert([
                        'content_id' => $item->content_id,
                        'language_id' => $targetLangId,
                        'description' => $descriptionValue
                    ]);
            }
        }
    }
}
