<?php

namespace App\Repositories;

use App\Interfaces\EventInterface;
use App\Models\Collection;
use App\Models\JobCategory;
use App\Models\Faculty;
use App\Models\Blog;
use App\Models\Event;
use App\Models\order;
use App\Models\Unit;
use App\Models\Subject;

class EventRepository implements EventInterface
{
//Event Management
    public function getSearchEvent(string $term)
    {
        return Event::where([['title', 'LIKE', '%' . $term . '%']])->where('deleted_at', 1)->get();
    }
    public function listAllEvents()
    {
        return Event::latest()->where('deleted_at', 1)->get();
    }
    public function findEventById($id)
    {
        return Event::findOrFail($id);
    }
//Blog Management
    public function getSearchBlog(string $term)
    {
        return Blog::where('title', 'LIKE', '%' . $term . '%')
        ->orWhere('event_category', 'LIKE', '%' . $term . '%')
        ->orWhere('short_desc', 'LIKE', '%' . $term . '%')
        ->where('deleted_at', null)
        ->get();
    }
    public function listAllBlogs()
    {
        return Blog::latest()->where('deleted_at', 1)->get();
    }
    public function findBlogById($id)
    {
        return Blog::findOrFail($id);
    }
}
?>
