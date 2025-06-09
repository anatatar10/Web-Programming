$(function () {
    const totalDesktops = 4;
    let current = 1;

    $(".container").on("click", function () {
      const currentDesktop = $("#desktop" + current);
      current = current % totalDesktops + 1; // move to next desktop, circular
      const nextDesktop = $("#desktop" + current);

      nextDesktop.css({
        top: "-100%", /* the next desktop is just above the visible page*/
        display: "flex" /*centers the content while keeping the setting from css */
      });

      // Animate current down, next in from top
      currentDesktop.animate({ top: "100%" }, 500, function () { /*top 100 means the transition is smooth; 500 is duration of animation */
        $(this).css({ display: "none"}); /* display none makes the current desktop disappear completely*/
      });

      nextDesktop.animate({ top: "0%" }, 500); /*gets the next desktop from above to the current page */
    });
  });