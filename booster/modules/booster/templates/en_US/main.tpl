{meta_html assets 'booster'}

<div id="top-box">
    <div class="top-container">
        <div id="accessibility">Quick links:
                <a href="#content">Content</a> -
                <a href="#topmenubar">sections</a> -
                <a href="#submenubar">sub sections</a>
        </div>       
        {zone 'jcommunity~status'}
        <div id="lang-box">
          <strong>EN</strong>
          <a href="{jurl 'booster~default:index', array('lang'=>'fr_FR')}" hreflang="fr" title="français">fr</a>
        </div>
    </div>
</div>


<div id="header">
    <div class="top-container">
        <h1 id="logo">
            <a href="/" title="Return to homepage"><img src="/images/logo_jelix_moyen4.png" alt="Jelix" /></a>
        </h1>
        <div id="topmenubar">
            <p>
                <a href="{jurl 'booster~default:index'}">Jelix BOOSTER</a>, the portal of resources produced by the community
            </p>
            <ul>
                {*<li {if $tout}class="selected"{/if}><a href="{jurl 'booster~default:index'}">All</a></li>*}
                <li {if $applis}class="selected"{/if}><a href="{jurl 'booster~default:applis'}">Applications</a></li>
                <li {if $modules}class="selected"{/if}><a href="{jurl 'booster~default:modules'}">Modules</a></li>
                <li {if $plugins}class="selected"{/if}><a href="{jurl 'booster~default:plugins'}">Plugins</a></li>
                <li {if $packlang}class="selected"{/if}><a href="{jurl 'booster~default:packlang'}">Languages Pack</a></li>
                {ifuserconnected}
                    <li {if $your_ressources}class="selected"{/if}><a href="{jurl 'booster~default:yourressources'}">{@booster~main.your.ressources@}</a></li>
                {/ifuserconnected}
            </ul>

        </div>
    </div>
</div>

<div id="main-content">
    <div class="top-container">
        <div id="content-header">
            <div id="submenubar">
                {zone 'booster~search'}
                {ifuserconnected}
                    <a class="jforms-submit" href="{jurl 'booster~default:add'}">{@booster~main.add.an.item@}</a>
                {/ifuserconnected}
            </div>
        </div>

        {ifuserconnected}
            {ifacl2 'booster.admin.index'}
                {zone 'boosteradmin~tasktodo'}
            {/ifacl2}
        {/ifuserconnected}

        <div id="content">
            {jmessage}
    
            {$MAIN}

        </div>
    </div>
</div>


<div id="footer">
    <div class="top-container">

        <div class="footer-box">
            <p><img alt="Jelix" src="/images/logo_jelix_moyen5.png"></p>
        </div>

        <div class="footer-box">
             <p>
                <a href="https://github.com/foxmask/Booster/issues/new">An issue ? let us know </a> - <a href="{jurl 'booster~default:credits'}">Credits</a> -
                <a href="{jurl 'booster~rss:index', array('lang' => $j_locale)}"><img src="{$j_basepath}booster/images/rss.png" alt="RSS Feed"/></a>
            </p>
        </div>

        <div id="footer-legend">
            Copyright 2006-2022 Jelix team. <br/>
            Design by Laurentj. <br/>
            <img alt="page generated with a Jelix application" src="{$j_jelixwww}design/images/jelix_powered.png">
        </div>
    </div>
</div>
