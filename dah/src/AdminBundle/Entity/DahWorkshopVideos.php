<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahWrokshopVideos
 */
class DahWorkshopVideos
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
     * @var \AdminBundle\Entity\DahWorkshops
     */
    private $wid;


    /**
     * Set videoTitle
     *
     * @param string $videoTitle
     * @return DahWrokshopVideos
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
     * @return DahWrokshopVideos
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
     * @return DahWrokshopVideos
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
     * @return DahWrokshopVideos
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
     * @return DahWrokshopVideos
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
     * @return DahWrokshopVideos
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
     * Set wid
     *
     * @param \AdminBundle\Entity\DahWorkshops $wid
     * @return DahWrokshopVideos
     */
    public function setWid(\AdminBundle\Entity\DahWorkshops $wid = null)
    {
        $this->wid = $wid;

        return $this;
    }

    /**
     * Get wid
     *
     * @return \AdminBundle\Entity\DahWorkshops 
     */
    public function getWid()
    {
        return $this->wid;
    }
}
