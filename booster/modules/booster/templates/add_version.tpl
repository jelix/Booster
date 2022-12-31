<div id="article">
    <h1>{jlocale 'booster~main.add.a.version.to', array($itemName)}</h1>
    {form $form, 'booster~default:saveVersion',array('itemId' => $itemId, 'itemName'=>$itemName)}
    <table class="jforms-table">
        <tr>
            <th>{ctrl_label 'version_name'}</th><td>{ctrl_control 'version_name'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'version_date'}</th><td>{ctrl_control 'version_date'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'stability'}</th><td>{ctrl_control 'stability'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'id_jelix_version'}</th><td>{ctrl_control 'id_jelix_version'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'id_jelix_version_max'}</th><td>{ctrl_control 'id_jelix_version_max'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'filename'}</th><td>{ctrl_control 'filename'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'download_url'}</th><td>{ctrl_control 'download_url'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'last_changes'}</th>
            <td>{ctrl_control 'last_changes'}</td>
        </tr>
    </table>
    <div> {formsubmit} </div>
    {/form}
    {include 'booster~zone.syntax_wiki'}
</div>
