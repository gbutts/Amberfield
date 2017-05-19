//  Snippet for search button on search.shtml
//  From "Creating Cool Web Sites" by Dave Taylor, Ch 9
//GRB Version 0.1 1/12/07

function tweakValue()
{
    if (document.searchbox.scope[0].checked)
        document.searchbox.q.value += " +site:amberfield.com.au";
}
