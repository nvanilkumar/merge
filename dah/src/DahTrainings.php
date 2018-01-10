<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * DahTrainings
 *
 * @ORM\Table(name="dah_trainings", indexes={@ORM\Index(name="FK_dah_workshops", columns={"deptid"}), @ORM\Index(name="FK_dah_trainings_uid", columns={"uid"})})
 * @ORM\Entity
 */
class DahTrainings
{
    /**
     * @var integer
     *
     * @ORM\Column(name="tid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tid;

    /**
     * @var string
     *
     * @ORM\Column(name="training_title", type="string", length=250, nullable=true)
     */
    private $trainingTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="training_description", type="text", nullable=true)
     */
    private $trainingDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="training_meta_title", type="string", length=250, nullable=true)
     */
    private $trainingMetaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="training_meta_keyword", type="string", length=250, nullable=true)
     */
    private $trainingMetaKeyword;

    /**
     * @var string
     *
     * @ORM\Column(name="training_meta_description", type="string", length=250, nullable=true)
     */
    private $trainingMetaDescription;

    /**
     * @var integer
     *
     * @ORM\Column(name="added_on", type="integer", nullable=true)
     */
    private $addedOn;

    /**
     * @var integer
     *
     * @ORM\Column(name="updated_on", type="integer", nullable=true)
     */
    private $updatedOn;

    /**
     * @var string
     *
     * @ORM\Column(name="public", type="string", nullable=true)
     */
    private $public = 'yes';

    /**
     * @var string
     *
     * @ORM\Column(name="assesment", type="string", nullable=true)
     */
    private $assesment = 'no';

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status = 'inactive';

    /**
     * @var integer
     *
     * @ORM\Column(name="tview", type="integer", nullable=true)
     */
    private $tview;

    /**
     * @var boolean
     *
     * @ORM\Column(name="featured", type="boolean", nullable=true)
     */
    private $featured = '0';

    /**
     * @var \DahDepartments
     *
     * @ORM\ManyToOne(targetEntity="DahDepartments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="deptid", referencedColumnName="deptid")
     * })
     */
    private $deptid;

    /**
     * @var \DahUsers
     *
     * @ORM\ManyToOne(targetEntity="DahUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="uid", referencedColumnName="uid")
     * })
     */
    private $uid;


}

