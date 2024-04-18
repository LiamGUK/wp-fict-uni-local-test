import $ from "jquery";

class Like {
  constructor() {
    this.events();
  }

  events() {
    $(".like-box").on("click", this.ourClickDispatcher.bind(this));
  }

  // Custom methods
  ourClickDispatcher(e) {
    const currentLikeBox = $(e.target).closest(".like-box");

    if (currentLikeBox.attr("data-exists") === "yes") {
      this.deleteLike(currentLikeBox);
    } else {
      this.createLike(currentLikeBox);
    }
  }

  createLike(currentLikeBox) {
    $.ajax({
      // Need to include nonce value in http request if wanting to validate if user is logged in route.php code
      beforeSend: (xhr) => {
        // use setRequestHeader to create new header for request and add in X-WP-NONCE with value of nonce value set up in functions.php under wp_localize_script hook. Will allow permission
        xhr.setRequestHeader("X-WP-NONCE", universityData.nonce);
      },
      url: universityData.root_url + "/wp-json/university/v1/manageLike",
      type: "POST",
      data: {
        // data property in ajax request same as adding a param value to url key
        professorId: currentLikeBox.data("professor"), // same as /wp-json/university/v1/manageLike?professorId=POST_ID
      },
      success: (response) => {
        // will update heart icon to the solid colour version
        currentLikeBox.attr("data-exists", "yes");
        let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
        likeCount++;
        currentLikeBox.find(".like-count").html(likeCount);
        currentLikeBox.attr("data-like", response);
        console.log(response);
      },
      error: (error) => {
        console.log(error);
      },
    });
  }

  deleteLike(currentLikeBox) {
    $.ajax({
      // Need to include nonce value in http request if wanting to validate if user is logged in route.php code
      beforeSend: (xhr) => {
        // use setRequestHeader to create new header for request and add in X-WP-NONCE with value of nonce value set up in functions.php under wp_localize_script hook. Will allow permission
        xhr.setRequestHeader("X-WP-NONCE", universityData.nonce);
      },
      url: universityData.root_url + "/wp-json/university/v1/manageLike",
      data: {
        like: currentLikeBox.attr("data-like"),
      },
      type: "DELETE",
      success: (response) => {
        currentLikeBox.attr("data-exists", "no");
        let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
        likeCount--;
        currentLikeBox.find(".like-count").html(likeCount);
        currentLikeBox.attr("data-like", "");
        console.log(response);
      },
      error: (error) => {
        console.log(error);
      },
    });
  }
}

export default Like;
