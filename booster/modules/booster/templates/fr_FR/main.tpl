{meta_html assets 'booster'}


<div id="top-box">
    <div class="top-container">
        <div id="accessibility">Raccourcis :
            <a href="#content">Contenu</a> -
            <a href="#topmenubar">rubriques</a> -
            <a href="#submenubar">sous rubriques</a>
        </div>
        {zone 'jcommunity~status'}
        <div id="lang-box">
          <a href="{jurl 'booster~default:index', array('lang'=>'en_US')}" hreflang="en" title="english">en</a>
          <strong>fr</strong>
        </div>
    </div>
</div>


<div id="header">
    <div class="top-container">
        <h1 id="logo">
            <a href="/" title="Page d'accueil du site"><img src="/images/logo_jelix_moyen4.png" alt="Jelix" /> Booster</a>
        </h1>
        <div id="topmenubar">
            <p>
                Tous les composants pour booster vos projets Jelix
            </p>
            <ul>
                <li {if $modules}class="selected"{/if}><a href="{jurl 'booster~default:modules'}">Modules</a></li>
                <li {if $libraries}class="selected"{/if}><a href="{jurl 'booster~default:libraries'}">Bibliothèques</a></li>
                <li {if $applis}class="selected"{/if}><a href="{jurl 'booster~default:applis'}">Applications</a></li>
                <li {if $plugins}class="selected"{/if}><a href="{jurl 'booster~default:plugins'}">Plugins</a></li>
                <li {if $packlang}class="selected"{/if}><a href="{jurl 'booster~default:packlang'}">Pack de Langues</a></li>
                {ifuserconnected}
                    <li {if $your_ressources}class="selected"{/if}><a href="{jurl 'booster~default:yourprojects'}">{@booster~main.your.projects@}</a></li>
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
                <a href="https://github.com/foxmask/Booster/issues/new">Un problème d'utilisation ? faites nous en part</a> - <a href="{jurl 'booster~default:credits'}">Crédits</a> -
                <a href="{jurl 'booster~rss:index', array('lang' => $j_locale)}"><img src="{$j_basepath}booster/images/rss.png" alt="Flux RSS"/></a>
            </p>
        </div>

        <div id="footer-legend">
            Copyright 2006-2023 Jelix team. <br/>
            Design par Laurentj. <br/>
            <img alt="page générée par une application Jelix" src="{$j_jelixwww}design/images/jelix_powered.png">
        </div>
    </div>
</div>
