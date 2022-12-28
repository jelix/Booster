<div id="recommended-block">
    {if $results->rowCount() > 0}
    {foreach $results as $item}
        <div class="content-box">
            {$item->type_name}
            <h4>
                <img src="{$j_themepath}icons/reco.png" height="12" width="12" alt=""/>
                <a href="{jurl 'booster~default:viewItem', array('id' => $item->id, 'name' => $item->name)}">{$item->name}</a>
            </h4>
            <p>
            {if ($j_lang == 'fr' && $item->slogan_fr != '') ||$item->slogan == ''}
                {$item->slogan_fr|eschtml}
            {else}
                {$item->slogan|eschtml}
            {/if}</p>

        </div>
    {/foreach}
    {else}
        {@booster~main.not.items.type@}
    {/if}
</div>
