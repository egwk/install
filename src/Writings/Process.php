<?php

namespace Egwk\Install\Writings;

/**
 * Process writings
 * Exports paragraphs by iterating through
 *      folders
 *          -> books
 *              -> TOCs
 *                  -> chapters
 *                      -> paragraphs
 *
 * @author Peter
 */
class Process
    {

	use \Egwk\Install\Writings\Tools\OperationCounter;

    /**
     *
     * @var Export\ExportInterface $export 
     */
    protected $export = null;

    /**
     *
     * @var API\Iterator $iterator 
     */
    protected $iterator = null;

    /**
     * Class constructor
     *  
     * @access public
     * @param \Egwk\Install\Writings\API\Iterator $iterator
     * @param \Egwk\Install\Writings\Export\Export $export
     * @return void
     */
    public function __construct(API\Iterator $iterator, Export\Export $export)
        {
        $this->export   = $export;
        $this->iterator = $iterator;
        $this->initCounter(0); // set positive value for testing 
        }

    /**
     * Process chapter, export paragraphs
     *  
     * @access protected
     * @param \stdClass $tocEntry
     * @return void
     */
    protected function chapter(\stdClass $tocEntry)
        {
        echo "      $tocEntry->para_id :: $tocEntry->title\n            ";
        list($bookId, $idElement) = explode('.', $tocEntry->para_id, 2);
        foreach ($this->iterator->chapter($bookId, $idElement) as $paragraph)
            {
            echo ".";
            $this->export->export($paragraph);
            $this->stepCounter();
            if ($this->counterTermSignal())
                {
                break;
                }
            }
        echo "\n";
        }

    /**
     * Process Table of Contents, export chapters
     *  
     * @access protected
     * @param \stdClass $book
     * @return void
     */
    protected function toc(\stdClass $book)
        {
        echo "  $book->code :: $book->title\n";
        foreach ($this->iterator->toc($book->book_id) as $tocEntry)
            {
            $this->chapter($tocEntry);
            if ($this->counterTermSignal())
                {
                break;
                }
            }
        }

    /**
     * Process books, export Table of Contents
     *  
     * @access protected
     * @param \stdClass $folder
     * @return void
     */
    protected function books(\stdClass $folder)
        {
        echo "$folder->folder_id - $folder->name\n";
        foreach ($this->iterator->books($folder->folder_id) as $book)
            {
            $this->toc($book);
            if ($this->counterTermSignal())
                {
                break;
                }
            }
        }

    /**
     * Process writings folders, export books.
     *  
     * @access public
     * @return void
     */
    public function writings()
        {
        foreach ($this->iterator->writings() as $folder)
            {
            $this->books($folder);
            if ($this->counterTermSignal())
                {
                break;
                }
            }
        }

    }
