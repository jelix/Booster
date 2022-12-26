{meta_html assets 'jquery_ui'}



{assign $github = strpos($data->url_repo , '//github.com') !== false}
{assign $bitbucket = strpos($data->url_repo , '//bitbucket.org') !== false}



<div class="booster-item">
<h3>
    {$data->type_name} <a class="booster-item-name" href="{jurl 'booster~viewItem',array('name'=>$data->name,'id'=>$data->id)}">{$data->name}</a> 
    {if $data->status == 0 || !empty($item_not_moderated)}
        <small>({@booster~main.not.moderated@})</small>
    {/if}
    {if $data->recommendation}
        <small><img src="{$j_themepath}icons/reco.png" height="12" width="12" alt="{@booster~main.is.reco@}" title="{@booster~main.reco.help@}"/></small>
    {/if}
    
</h3>

    {if !empty($item_not_moderated)}
        <div class="section" id="item-not-moderated">
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
        </div>

        {zone 'booster~tagsitem',array('id'=>$data->id)}

        <div class="booster-item-desc section">
            {if $data->dev_status == 1}
                <p class="dev-status dev-status-unmaintained">{@booster~main.dev_status.unmaintained@}</p>
            {elseif $data->dev_status == 2}
                <p class="dev-status dev-status-gone">{@booster~main.dev_status.gone@}</p>
            {/if}
            <div class="desc">
            {if $j_lang == 'fr' && $data->short_desc_fr != ''}
                {$data->short_desc_fr|wiki:'wr3_to_xhtml'}
            {else}
                {$data->short_desc|wiki:'wr3_to_xhtml'}
            {/if}
            </div>
        </div>
    </div>   

     <div class="booster-item-infos section">
            <ul class="inline-list">
                <li>
                    <img src="{$j_themepath}icons/user_edit.png" alt=""/>
                    {@booster~main.author@} {$data->author}
                </li>
                <li>
                    <img src="{$j_themepath}icons/user_gray.png" alt=""/>
                    {@booster~main.item_by@} {zone 'booster~author', array('id'=>$data->item_by)}
                </li>

                
                {if $data->url_website != null}
                    <li class="booster_url">
                        <img src="{$j_themepath}icons/world.png" alt=""/>
                        <a href="{$data->url_website}">{@booster~main.website@}</a>
                    </li>
                {/if}
                {if $data->url_repo != null }
                    <li>
                        {if $github}
                            <img src="{$j_themepath}icons/github.png" alt=""/>
                        {elseif $bitbucket}
                            <img src="{$j_themepath}icons/bitbucket.png" alt=""/>
                        {/if}
                        <a href="{$data->url_repo}">{@booster~main.repository@}</a>
                    </li>
                {/if}
                {if $data->url_download}
                    <li class="booster_url">
                        <img src="{$j_themepath}icons/world.png" alt=""/>
                        <a href="{$data->url_download}">{@booster~main.item_download_url@}</a>
                    </li>
                {/if}

                {if $data->item_composer_id}
                    <li class="booster_url">
                        <a href="https://packagist.org/packages/{$data->item_composer_id}">{@booster~main.item_composer_id.label@} {$data->item_composer_id}</a>
                    </li>
                {/if}

            </ul>
    </div>
    {if $github}
        {*zone 'booster~item_github', array('url_repo'=>$data->url_repo)*}
    {/if}

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
                        <a href="{jurl 'booster~default:_addVersion',array('id'=>$data->id,'name'=>$data->name)}">{@booster~main.add.a.version@}</a>
                    </li>
                {/if}

                {if $recommendation}
                    <li>
                        <form action="{formurl 'booster~default:recommendation'}" method="POST">
                            <div>
                                 <img src="{$j_themepath}icons/reco.png" alt=""/>
                                {formurlparam}
                                <input type="hidden" name="id" value="{$data->id}"/>
                                <input type="hidden" name="name" value="{$data->name}"/>
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


    {zone "booster~versions",array('id'=>$data->id, 'canEditVersion' => $canEditVersion)}

</div>