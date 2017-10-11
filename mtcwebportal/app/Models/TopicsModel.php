<?php

namespace App\Models;

use DB;

class TopicsModel extends CommonModel
{

    /**
     * To get the topic details
     * @return type 
     */
    public function getTopicComments()
    {
        $topics = DB::table('topics')
                ->leftjoin('comments', 'comments.topic_id', '=', 'topics.topic_id')
                ->leftjoin('users', 'users.user_id', '=', 'comments.created_by')
                ->select('comments.comment_id','comment_text','comments.topic_id','comments.created_at',
		'comments.updated_at','topic_title','topic_description','comments.status','topics.topic_id',
		'topics.created_at as topic_created_at', 'topics.updated_at as topic_updated_at',
		'users.first_name','users.last_name')
                ->where($this->where)
                 ->orderBy('comments.created_at', 'desc')
                ->get();
        if (count($topics) == 0) {
            return NULL;
        }

        return $topics;
    }
    
    /**
     * To remove the specified topics related comments
     * @param type $topicListString
     * @return boolean
     */
    public function deleteComments($topicListString)
    {
        DB::delete('delete from comments where topic_id in ('.$topicListString.')');
        return true;
    }        
    
}
