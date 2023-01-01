{assign $count = 0}
<div class="booster-versions">

{if $versions->rowCount() >= 1}

    <h4>{@booster~main.versions.last@}</h4>

    {foreach $versions as $version}

        {if $count == 1}
            {assign $count = -1}
            <h4 id="booter_old_version">{@booster~main.old.versions@}</h4>
        {/if}

        <div class="booster-version section {if $count == 0}last-version {assign $count= 1}{/if}">
            <h5 class="booster-version-title">{$version->version_name|eschtml} <small>({$version->stability})</small></h5>
            <ul class="inline-list">
                <li>
                    <img src="{$j_themepath}icons/date.png" alt=""/>
                    {@booster~main.version_date_on@} {$version->version_date|jdatetime:'db_date':'lang_date'}
                </li>

                {if $version->version_min || $version->version_max}
                <li class="compatibility">
                    <img src="{$j_themepath}icons/wrench_orange.png" alt=""/>
                    {@booster~main.compatible@}
                    {if $version->version_min}
                    <span class="jelix-version">{$version->version_min}</span>
                        {if $version->version_max && $version->version_max != $version->version_min}
                            {@booster~main.to@} <span class="jelix-version">{$version->version_max}</span>
                        {/if}
                    {elseif $version->version_max}
                        <span class="jelix-version">{$version->version_max}</span>
                    {/if}
                </li>
                {/if}

                {if $version->download_url}
                <li>
                    <img src="{$j_themepath}icons/disk.png" alt=""/>
                    {if $version->filename}
                    {@booster~main.download@} : <a href="{$version->download_url|eschtml}">{$version->filename|eschtml}</a>
                    {else}
                        <a href="{$version->download_url|eschtml}">{@booster~main.download@}</a>
                    {/if}
                </li>
                {/if}

                {if $canEditVersion}
                    <li>
                        <img src="{$j_themepath}icons/version_edit.png" alt=""/>
                        <a href="{jurl 'booster~editVersion',array('id'=>$version->id)}">{@booster~main.edit@}</a>
                    </li>
                {else}
                    {ifacl2 'booster.edit.version'}
                        <li>
                            <img src="{$j_themepath}icons/version_edit.png" alt=""/>
                            <a href="{jurl 'booster~editVersion',array('id'=>$version->id)}">{@booster~main.edit@}</a>
                        </li>
                    {/ifacl2}
                {/if}
                   
            </ul>
            {if $version->last_changes}
            <div class="booster-version-body">

                <h6>{@booster~main.version.changelog@}</h6>
                <blockquote class="desc">{$version->last_changes|wiki:'wr3_to_xhtml'}</blockquote>

            </div>{/if}
        </div>

    {/foreach}
{/if}
</div>
