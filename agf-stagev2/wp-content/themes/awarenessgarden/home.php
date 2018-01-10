<?php
/*
Template Name: Home Page
*/ 
get_header('home');                     
?>
<div id="content">  
<table style="width: 100%;">
  <tbody>
  <tr>
    <td id="contentMiddleWrapper" style="vertical-align: top;">
        <div id="contentMiddleContent" style="min-height: 200px;">
            <div id="contents">
                <div id="contents-in">
                    <div class="home-float">
                        <h1><a style="color: #666;" title="Buy a Brick, Build a Garden" href="<?php bloginfo("url"); ?>/about-awareness-garden">GARDEN</a></h1>
                        <div class="main-float-data">The Awareness Garden in Lynchburg, VA honors the families, friends and caregivers whose lives have been touched by cancer. The Garden provides the opportunity to honor a loved one with engraved bricks and other naming opportunities. It is a peaceful place to go to reflect on one's journey of healing.</div>
                        <a class="tolink" title="Buy a Brick, Build a Garden" href="<?php bloginfo("url"); ?>/about-awareness-garden">Learn more about us</a>.
  
                    </div>
  
                    <div class="home-float" style="border-left: solid 1px #ccc; border-right: solid 1px #ccc;">
                        <h1><a style="color: #666;" title="Donate to the Endowment" href="<?php bloginfo("url"); ?>/donation">FOUNDATION</a></h1>
                        <div class="main-float-data">The Awareness Garden Endowment Fund was established to foster cancer awareness with educational outreach programs throughout our community and to contribute to scholarships for those affected by cancer or interested in cancer-related work.</div>
                        <a class="tolink" title="Donate to the Endowment" href="<?php bloginfo("url"); ?>/donation">Donate to the Foundation</a>.  
                    </div>
                    
                    <div class="home-float">
                        <h1><a style="color: #666;" title="Scholarship Program" href="<?php bloginfo("url"); ?>/education">EDUCATION</a></h1>
                        <div class="main-float-data">The Awareness Garden helps to bring programs about cancer prevention, detection and treatment to community citizens and groups. In the last seven years, with the help of generous donations we have given over $54,000 through our scholarship program, to deserving individuals whose lives have been affected by cancer.</div>
                        <a class="tolink" title="Scholarship Program" href="<?php bloginfo("url"); ?>/education">Education &amp; Scholarship Programs</a>.
                    </div>
                </div>
            </div>
        </div>
    </td>
  </tr>
  </tbody>
</table>
</div>
<?php get_footer('home'); ?>