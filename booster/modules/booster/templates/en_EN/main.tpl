{meta_html csstheme 'css/reset.css'}
{meta_html csstheme 'css/text.css'}
{meta_html csstheme 'css/booster.css'}
{meta_html cssthemeie 'css/ie.css'}
{meta_html cssthemeltie7 'css/ie6.css'}
<div id="top-box">
    <div id="accessibility">Quick links:
        <a href="#content">Content</a> -
        <a href="#topmenubar">sections</a> -
        <a href="#submenubar">sub sections</a>
    </div>
    <div id="lang-box">
      <strong>EN</strong>
      <a href="{jurl 'booster~default:index', array('lang'=>'fr_FR')}" hreflang="fr" title="français">fr</a>
    </div>
</div>

  <h1 id="logo"><a href="/" title="Return to homepage"><img src="/logo_jelix_moyen2.png" alt="Jelix" /></a><br/>
  PHP5 Framework
  </h1>

<div id="header">
    <div id="topmenubar">
        <a href="{jurl 'booster~default:index'}">Jelix BOOSTER</a>
    </div>
    <div id="submenubar">
        <ul>
            <li {if $tout}class="selected"{/if}><a href="{jurl 'booster~default:index'}">All</a></li>
            <li {if $applis}class="selected"{/if}><a href="{jurl 'booster~default:applis'}">Applications</a></li>
            <li {if $modules}class="selected"{/if}><a href="{jurl 'booster~default:modules'}">Modules</a></li>
            <li {if $plugins}class="selected"{/if}><a href="{jurl 'booster~default:plugins'}">Plugins</a></li>
            <li {if $packlang}class="selected"{/if}><a href="{jurl 'booster~default:packlang'}">Languages Pack</a></li>
        </ul>
        {zone 'jcommunity~status'}
    </div>
</div>

<div id="main">
{if $is_home}
    <h1>Booster : What's that ?</h1>
    <p id="booster_description">
        Booster is the portal that provides all the existing Jelix ressources provided by the community :
        Applications, Modules, Plugins, and Language Packs. <a href="{jurl 'booster~default:add'}">You can now add your own work</a> on <em>Booster</em>.
    </p>
{/if}
    <div id="content-menu">
        {$MENU}
    </div>
    <div id="content">
        {if $is_home}<h2>Welcome to Booster.</h2>{/if}
        {jmessage}
        {$MAIN}
        <p style="clear: both;"/>
    </div>
</div>

<div id="footer" class="full">
    <a href="/articles/fr/credits">Contacts &amp; Credits</a> - Copyright 2006-2011 Jelix team.<br/>
    <img src="/btn_jelix_powered.png" alt="page generated by Jelix" />
</div>
