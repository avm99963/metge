window.addEventListener("load", function() {
  var dialogs = document.querySelectorAll("dialog");
  for (var i = 0; i < dialogs.length; i++) {
    dialogPolyfill.registerDialog(dialogs[i]);
  }

  document.querySelector(".adduser").addEventListener("click", function() {
    document.querySelector("#adduser").showModal();
    /* Or dialog.show(); to show the dialog without a backdrop. */
  });

  document.querySelector(".importcsv").addEventListener("click", function() {
    document.querySelector("#importcsv").showModal();
    /* Or dialog.show(); to show the dialog without a backdrop. */
  });
});
