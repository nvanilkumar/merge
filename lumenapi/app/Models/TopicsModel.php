<?php

namespace App\Models;

use DB;

class TopicsModel extends CommonModel
{

    /**
     * To get the topic related comment details
     * @return type 
     */
    public function getTopic()
    {
        $data = [];
        $topics = DB::table('topics')
                ->leftJoin('comments', 'comments.topic_id', '=', 'topics.topic_id')
                ->leftJoin('users', 'comments.created_by', '=', 'users.user_id')
//                ->select('topic_title', 'topic_description','topics.created_at as topics_created', 'category_id', 'comment_id', 'comment_text',
//                        'comments.created_at','users.first_name','users.last_name')
                   ->select(DB::raw("topic_title,topic_description, category_id,
	FORMAT ( topics.created_at, N'yyyy-MM-dd HH:mm:ss') AS topics_created,
	FORMAT ( comments.created_at, N'yyyy-MM-dd HH:mm:ss') AS created_at,
		comment_id,comment_text,users.first_name,users.last_name "))
                ->where($this->where);


        $count = $topics->count();
        if ($this->startingIndex > 0) {
            $topics->offset($this->startingIndex);
        }
        $topics = $topics->limit($this->recordsPerPage)
                ->orderBy('comments.comment_id', 'desc')
                ->get();

        if (count($topics) == 0) {
            return NULL;
        }

        $data['count'] = $count;
        $data['records'] = $topics;
        return $data;
    }

    public function getCategoriesList()
    {
        $whereString = "";
        if ($this->where) {
            $whereString = " where (category_name like '%" . $this->where['category_name'] . "%') ";
        }
        $select = "select categories.category_id, max(category_name) as category_name,
                   count(topic_id) as topics_count
                   from categories
                   left join topics on topics.category_id=categories.category_id"
                . $whereString . "
                   group by categories.category_id order by categories.category_id desc";

        $categories = DB::select(DB::raw($select));
        if (count($categories) == 0) {
            return NULL;
        }
        return $categories;
    }

    /**
     * To bring the topic list with pagination
     */
    public function getTopicList()
    { 
        $data = [];
 
        $topics = DB::table('topics')
                ->where($this->where);


        $count = $topics->count();
 
        if ($this->startingIndex > 0) {
            $topics->offset($this->startingIndex);
        }
        $topics = $topics->limit($this->recordsPerPage)
                ->leftJoin('comments', 'comments.topic_id', '=', 'topics.topic_id')
                ->selectRaw('topics.topic_id, max(topic_title) as topic_title, max(topic_description) as topic_description, max(category_id) '
                        . 'as category_id,count(comment_id) as comments_count')
                ->groupBy('topics.topic_id')
                ->orderByRaw('max(topics.updated_at) desc')
                ->get();

        if (count($topics) == 0) {
            return NULL;
        }

        $data['count'] = $count;
        $data['records'] = $topics;
        return $data;
    }

}
