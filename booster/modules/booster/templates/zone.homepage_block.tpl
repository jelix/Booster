<div class="homepage-block block-type-{$type} content-box">
        <h2>{jlocale 'booster~main.type.id.'.$type}</h2>

        <div class="content">
            {if $results->rowCount() > 0}
            <ul>
            {foreach $results as $item}

                <li>
                    <strong><a href="{jurl 'booster~default:viewItem', array('id' => $item->id, 'name' => $item->name)}">{$item->name|eschtml}</a></strong>
                    <span class="date"> - {$item->modified|jdatetime:'db_datetime':'lang_date'}</span>
                </li>
            {/foreach}
            </ul>
            {/if}

            {if $results->rowCount() > 0}
                <p class="browse-list">
                    {if $type == 1}
                        <a href="{jurl 'booster~default:applis'}">
                    {elseif $type == 2}
                        <a href="{jurl 'booster~default:modules'}">
                    {elseif $type == 3}
                        <a href="{jurl 'booster~default:plugins'}">
                    {elseif $type == 4}
                        <a href="{jurl 'booster~default:packlang'}">
                    {elseif $type == 5}
                        <a href="{jurl 'booster~default:libraries'}">
                    {/if}
                    {jlocale 'booster~main.see.list.type.id.'.$type}</a>
                </p>
            {else}
                {@booster~main.not.items.type@}
            {/if}
        </div>
</div>
