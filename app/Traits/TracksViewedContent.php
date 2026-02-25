<?php

namespace App\Traits;

trait TracksViewedContent
{
    public function hasViewedContent(int $contentId): bool
    {
        $viewedContents = session()->get('viewed_contents', []);

        return in_array($contentId, $viewedContents);
    }

    public function markContentAsViewed(int $contentId): void
    {
        $viewedContents = session()->get('viewed_contents', []);

        if (! in_array($contentId, $viewedContents)) {
            $viewedContents[] = $contentId;
            session()->put('viewed_contents', $viewedContents);
        }
    }
}
