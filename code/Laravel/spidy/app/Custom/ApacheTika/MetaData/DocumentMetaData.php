<?php
namespace App\Custom\ApacheTika\MetaData;

use Exceptions;
use DateTime;
use DateTimeZone;

class DocumentMetaData extends MetaData
{
    public $title = null;
    public $description = null;
    public $keywords = [];
    public $language = null;
    public $author = null;
    public $generator = null;
    public $pages = 0;
    public $words = 0;

    protected function setAttribute($key, $value)
    {
        $timezone = new DateTimeZone('IST');
        if (is_array($value)) 
        {
            $value = array_shift($value);
        }
        switch (mb_strtolower($key)) 
        {
            case 'title':
                $this->title = $value;
                break;
            case 'comments':
                $this->description = $value;
                break;
            case 'keyword':
            case 'keywords':
                if (preg_match('/,/', $value)) {
                    $value = preg_split('/\s*,\s*/', $value);
                } else {
                    $value = preg_split('/\s+/', $value);
                }
                $this->keywords = array_unique($value);
                break;
            case 'language':
                $this->language = mb_substr($value, 0, 2);
                break;
            case 'author':
            case 'initial-creator':
                $this->author = $value;
                break;
            case 'content-type':
                $value = preg_split('/;\s+/', $value);
                $this->mime = array_shift($value);
                break;
            case 'application-name':
            case 'generator':
            case 'producer':
                $value = preg_replace('/\$.+/', '', $value);
                $this->generator = trim($value);
                break;
            case 'nbpage':
            case 'page-count':
            case 'xmptpg:npages':
                $this->pages = (int) $value;
                break;
            case 'nbword':
            case 'word-count':
                $this->words = (int) $value;
                break;
            case 'creation-date':
            case 'date':
                $value = preg_replace('/\.\d+/', 'Z', $value);
                $this->created = new DateTime($value, $timezone);
                break;
            case 'last-modified':
                $value = preg_replace('/\.\d+/', 'Z', $value);
                $this->updated = new DateTime($value, $timezone);
                break;
            default:
                return false;
        }
        return true;
    }
}
?>