<?php

declare(strict_types=1);

namespace Carvago\Mrqe\Notes;

use Carvago\Mrqe\GitLab\GitLabRequestService;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;

class NotesFacade
{
    private const NOTES_EP = '/projects/%s/merge_requests/%s/notes';

    private const TYPE = 'DiffNote';

    public function __construct(private GitLabRequestService $gitLabRequestService)
    {
    }

    public function getResolvedNotes(int $projectId, int $iid): Int
    {
        $notes = json_decode($this->fetchNotesForMergeRequest($projectId, $iid)->getBody()->getContents());

        $count = 0;
        foreach ($notes as $note) {
            if($note->resolvable === true && $note->resolved === true){
                $count++;
            }
        }

        return $count;
    }

    private function fetchNotesForMergeRequest(int $projectId, int $iid): ResponseInterface
    {
        $uri = new Uri(sprintf(self::NOTES_EP, $projectId, $iid));
        return $this->gitLabRequestService->createAndSendResponse($uri);
    }
}