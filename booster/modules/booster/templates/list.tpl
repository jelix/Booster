    
    
<h2>{$title}</h2>
{if $description}
    <p class="category-description">{$description|eschtml}</p>
{/if}
{if $datas->rowCount() > 0}
    {assign $show_all_versions = false}
    {foreach $datas as $data}
        {include 'booster~view_item'}
    {/foreach}
    

    {if $datas->rowCount() < $count}
    	<div class="pagination">
    		{pagelinks_custom $action, array(), $count, $index_result, $per_page, $param_name}
    	</div>
	{/if}


{else}
    {@booster~main.not.items.type@}
{/if}