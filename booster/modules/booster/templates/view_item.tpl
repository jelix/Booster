{meta_html assets 'jquery_ui'}



{assign $github = strpos($data->url_repo , '//github.com') !== false}
{assign $bitbucket = strpos($data->url_repo , '//bitbucket.org') !== false}



<div class="booster-item">
    <div class="booster-item-header">

<h3>
    {$data->type_name} <a class="booster-item-name" href="{jurl 'booster~viewItem',array('name'=>$data->name,'id'=>$data->id)}">{$data->name}</a> 
    {if $data->status == 0 || !empty($item_not_moderated)}
        <small>({@booster~main.not.moderated@})</small>
    {/if}
    {if $data->recommendation}
        <small><img src="{$j_themepath}icons/reco.png" height="12" width="12" alt="{@booster~main.is.reco@}" title="{@booster~main.reco.help@}"/></small>
    {/if}
    
</h3>
        <div class="booster-item-author">{@booster~main.by@} {$data->author}</div>
    </div>
    {if $data->dev_status == 1}
        <p class="dev-status dev-status-unmaintained booster-item-warning">
            <img src="{$j_themepath}icons/exclamation.png" alt=""/>
            {@booster~main.dev_status.project.unmaintained@}</p>
    {elseif $data->dev_status == 2}
        <p class="dev-status dev-status-gone booster-item-warning">
            <img src="{$j_themepath}icons/exclamation.png" alt=""/>
            <strong>{@booster~main.dev_status.project.gone@}</strong></p>
    {/if}
    {if !empty($item_not_moderated)}
        <div class="section booster-item-warning" id="item-not-moderated">
            <h4><img src="{$j_themepath}icons/exclamation.png" alt=""/>
                {@booster~main.item_not_moderated@}
            </h4>
            {if $current_user == $data->item_by}
                <div>{@booster~main.your.item.is.not.moderated.yet@}</div>
            {/if}
        </div>
    {/if}
    <div class="wrapper-section">

        <div class="booster-item-image">
            {zone 'booster~itemimage', array('id'=>$data->id)}

            <ul>
                {if $data->url_website}
                    <li class="booster_url">
                        <img src="{$j_themepath}icons/world.png" alt=""/>
                        <a href="{$data->url_website}">{@booster~main.website@}</a>
                    </li>
                {/if}
                {if $data->url_repo}
                    <li>
                        {if $github}
                            <img src="{$j_themepath}icons/github.png" alt=""/>
                        {elseif $bitbucket}
                            <img src="{$j_themepath}icons/bitbucket.png" alt=""/>
                            {else}
                            <img src="{$j_themepath}icons/page_white_text.png" alt=""/>
                        {/if}
                        <a href="{$data->url_repo}">{@booster~main.source.code@}</a>
                    </li>
                {/if}
                {if $data->url_download}
                    <li class="booster_url">
                        <img src="{$j_themepath}icons/arrow_down.png" alt=""/>
                        <a href="{$data->url_download}">{@booster~main.download.link@}</a>
                    </li>
                {/if}

                {if $data->item_composer_id}
                    <li class="booster_url">
                        <img src="{$j_themepath}icons/box.png" title="{@booster~main.item_composer_id.label@}"  alt="{@booster~main.item_composer_id.label@}"/>
                        <a href="https://packagist.org/packages/{$data->item_composer_id}">{$data->item_composer_id}</a>
                    </li>
                {/if}
            </ul>

            {if $github}
                {*zone 'booster~item_github', array('url_repo'=>$data->url_repo)*}
            {/if}
        </div>

        <div class="booster-item-desc section">

            <div class="desc">
            {if ($j_lang == 'fr' && $data->short_desc_fr != '') ||$data->short_desc == ''}
                {$data->short_desc_fr|wiki:'wr3_to_xhtml'}
            {else}
                {$data->short_desc|wiki:'wr3_to_xhtml'}
            {/if}
            </div>
        </div>
        <div class="booster-item-infos">
            {zone 'booster~tagsitem',array('id'=>$data->id)}

            {zone 'booster~author', array('id'=>$data->item_by)}
        </div>

    </div>

    {assign $canEditVersion = false}
    {assign $editRight = false}
    {assign $recommendation = false}

    {if empty($item_not_moderated) && $data->item_by == $current_user}
        {assign $canEditVersion = true}
    {/if}

    {ifacl2 'booster.edit.item'}
        {assign $editRight = true}
    {/ifacl2}

    {ifacl2 'booster.recommendation'}
        {assign $recommendation = true}
    {/ifacl2}

    {if $canEditVersion || $editRight || $recommendation}
        <div class="booster-item-action section admin">
            <ul class="inline-list">


                {if $canEditVersion || $editRight}
                    <li>
                        <img src="{$j_themepath}icons/item_edit.png" alt=""/>
                        <a href="{jurl 'booster~default:editItem',array('id'=>$data->id,'name'=>$data->name)}">{@booster~main.edit.item@}</a>
                    </li>
                    <li>
                        <img src="{$j_themepath}icons/version_add.png" alt=""/>
                        <a href="{jurl 'booster~default:addVersion',array('id'=>$data->id,'name'=>$data->name)}">{@booster~main.add.a.version@}</a>
                    </li>
                {/if}

                {if $recommendation}
                    <li>
                        <form action="{formurl 'booster~default:recommendation', array('id'=>$data->id, 'name'=>$data->name)}" method="POST">
                            <div>
                                 <img src="{$j_themepath}icons/reco.png" alt=""/>
                                {formurlparam}
                                {if $data->recommendation}
                                    <input type="hidden" name="state" value="0"/>
                                    <input type="submit" class="link" value="{@booster~main.reco.item.cancel@}"/>
                                {else}
                                    <input type="hidden" name="state" value="1"/>
                                    <input type="submit" class="link" value="{@booster~main.reco.item@}"/>
                                {/if}
                            </div>
                        </form>
                    </li>
                {/if}

            </ul>
        </div>
    {/if}


    {zone "booster~versions", array('id'=>$data->id, 'canEditVersion' => $canEditVersion, 'show_all_versions'=>$show_all_versions)}

</div>