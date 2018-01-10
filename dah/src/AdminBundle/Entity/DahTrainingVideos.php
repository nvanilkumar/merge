<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahTrainingVideos
 */
class DahTrainingVideos
{
    /**
     * @var string
     */
    private $videoTitle;

    /**
     * @var string
     */
    private $video;

    /**
     * @var string
     */
    private $videoUrl;
    
    /**
     * @var string
     */
    private $videoDesc;
    
    /**
     * @var string
     */
    private $videoThumbnail;

    /**
     * @var string
     */
    private $status;

    /**
     * @var integer
     */
    private $tvid;

    /**
     * @var \AdminBundle\Entity\DahTrainings
     */
    private $tid;


    /**
     * Set videoTitle
     *
     * @param string $videoTitle
     * @return DahTrainingVideos
     */
    public function setVideoTitle($videoTitle)
    {
        $this->videoTitle = $videoTitle;

        return $this;
    }

    /**
     * Get videoTitle
     *
     * @return string 
     */
    public function getVideoTitle()
    {
        return $this->videoTitle;
    }

    /**
     * Set video
     *
     * @param string $video
     * @return DahTrainingVideos
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set videoUrl
     *
     * @param string $videoUrl
     * @return DahTrainingVideos
     */
    public function setVideoUrl($videoUrl)
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }

    /**
     * Get videoUrl
     *
     * @return string 
     */
    public function getVideoUrl()
    {
        return $this->videoUrl;
    }
    
    /**
     * Set videoDesc
     *
     * @param string $videoDesc
     * @return DahTrainingVideos
     */
    public function setVideoDesc($videoDesc)
    {
        $this->videoDesc = $videoDesc;

        return $this;
    }

    /**
     * Get videoDesc
     *
     * @return string 
     */
    public function getVideoDesc()
    {
        return $this->videoDesc;
    }
    
    /**
     * Set videoThumbnail
     *
     * @param string $videoThumbnail
     * @return DahTrainingVideos
     */
    public function setVideoThumbnail($videoThumbnail)
    {
        $this->videoThumbnail = $videoThumbnail;

        return $this;
    }

    /**
     * Get videoThumbnail
     *
     * @return string 
     */
    public function getVideoThumbnail()
    {
        return $this->videoThumbnail;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return DahTrainingVideos
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get tvid
     *
     * @return integer 
     */
    public function getTvid()
    {
        return $this->tvid;
    }

    /**
     * Set tid
     *
     * @param \AdminBundle\Entity\DahTrainings $tid
     * @return DahTrainingVideos
     */
    public function setTid(\AdminBundle\Entity\DahTrainings $tid = null)
    {
        $this->tid = $tid;

        return $this;
    }

    /**
     * Get tid
     *
     * @return \AdminBundle\Entity\DahTrainings 
     */
    public function getTid()
    {
        return $this->tid;
    }
}
