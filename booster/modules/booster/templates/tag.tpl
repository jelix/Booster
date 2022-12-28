   <h2>{jlocale 'main.items.tag.with', array($tag)}</h2>
   {assign $show_all_versions = false}
    {foreach $items as $data}
        {include 'booster~view_item'}
    {/foreach}