<?php

namespace Codense\TwitterListsExporter;

class Converter
{

    protected $format;
    protected $path;

    public function __construct($format, $path = '')
    {
        $format = mb_strtolower($format);
        if (in_array($format, ['json', 'html'])) {
            $this->format = $format;
            $this->path = $path;
        } else {
            throw new \Exception('Invalid format: ' . $format);
        }
    }

    public function convert(array $lists)
    {
        $this->{$this->format}($lists);
    }

    public function json(array $lists)
    {
        file_put_contents($this->path, json_encode($lists));
    }

    public function html(array $lists)
    {
        $html = '';
        foreach ($lists as $list) {
            $html .= "<h3>{$list->name}</h3>";
            $html .= "<ul>";
            foreach ($list->members as $member) {
                $html .= "<li><a href=\"https://twitter.com/{$member->screen_name}\">{$member->screen_name}</a></li>";
            }
            $html .= "</ul>";
        }
        file_put_contents($this->path, $html);
    }

}
