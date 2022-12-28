<h2>{@main.your.projects.title@}</h2>
    {assign $show_all_versions = false}
    {foreach $datas as $data}
        {assign $item_not_moderated = !$data->status}
        {include 'booster~view_item'}
    {/foreach}