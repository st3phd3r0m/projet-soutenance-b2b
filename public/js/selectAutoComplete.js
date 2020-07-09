$(document).ready(function() {
    $('.actSect').select2({
        matcher: matchStart
    })
});

function matchStart (term, text) {
    if (text.toUpperCase().indexOf(term.toUpperCase()) == 0) {
      return true;
    }
  
    return false;
}
  
// $.fn.select2.amd.require(['select2/compat/matcher'], function (oldMatcher) {
//     $("select").select2({
//         matcher: oldMatcher(matchStart)
//     })
// });