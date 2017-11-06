<?php

namespace Egwk\Install\Writings\API;

use Egwk\Install\Writings\API\Request;

/**
 * Description of Iterator
 *
 * @author Peter
 */
class Iterator
    {

    const SKIP_FOLDER_LIST = ['Indexes', 'Annotated', 'Biography',];

    protected $request = null;

    public function __construct(Request $request)
        {
        $this->request = $request;
        }

    public function writings()
        {
        foreach ($this->request->get("/content/languages/en/folders/") as $topFolder)
            {
            if ("EGW Writings" == $topFolder->name)
                {
                yield from $this->writingsChildren($topFolder->children);
                }
            }
        }

    protected function writingsChildren($topFolder)
        {
        foreach ($topFolder as $folder)
            {
            if (!in_array($folder->name, self::SKIP_FOLDER_LIST))
                {
                if (!empty($folder->children))
                    {
                    yield from $this->writingsChildren($folder->children);
                    }
                else
                    {
                    yield $folder;
                    }
                }
            }
        }

    public function chapter($bookId, $chapterId)
        {
        foreach ($this->iterate("/content/books/$bookId/chapter/$chapterId/") as $paragraph)
            {
            yield $paragraph;
            }
        }

    public function toc($bookId)
        {
        foreach ($this->iterate("/content/books/$bookId/toc/") as $book)
            {
            yield $book;
            }
        }

    public function paragraphs($bookId, $idElement)
        {
        foreach ($this->iterate("/content/books/$bookId/content/$idElement/") as $book)
            {
            yield $book;
            }
        }

    public function books($folder = null)
        {
        $byFolder = null === $folder ? '' : "by_folder/$folder/";
        foreach ($this->iterate('/content/books/' . $byFolder) as $book)
            {
            yield $book;
            }
        }

    protected function resultPages($parentItem, string $nextField)
        {
        $hasNext = false;
        do
            {
            foreach ($parentItem->results as $item)
                {
                yield $item;
                }
            $hasNext = $parentItem->{$nextField} !== null;
            if ($hasNext)
                {
                $parentItem = $this->request->getAPI()->request('GET', $parentItem->{$nextField});
                }
            }
        while ($hasNext);
        }

    protected function resultItems(array $items)
        {
        foreach ($items as $item)
            {
            yield $item;
            }
        }

    protected function iterate(string $command, $parameters = [], $nextField = "next")
        {
        $items = $this->request->get($command, $parameters);
        if (null !== $items)
            {
            if (!isset($items->results))
                {
                yield from $this->resultItems($items);
                }
            else
                {
                yield from $this->resultPages($items, $nextField);
                }
            }
        return [];
        }

    }
