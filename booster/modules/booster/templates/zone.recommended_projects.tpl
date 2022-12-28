<div id="recommended-block">
    {if $results->rowCount() > 0}
    {foreach $results as $item}
        <div class="content-box">
            {$item->type_name}<br>
            <strong>
                <img src="{$j_themepath}icons/reco.png" height="12" width="12" alt=""/>
                <a href="{jurl 'booster~default:viewItem', array('id' => $item->id, 'name' => $item->name)}">{$item->name}</a></strong><br>
            {@booster~main.by@} {$item->author}
        </div>
    {/foreach}
    {else}
        {@booster~main.not.items.type@}
    {/if}
</div>
