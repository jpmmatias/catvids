const likeVideo = (btn,videoId)=>{ 
    $.post("ajax/likeVideo.php",{videoId}).done(res=>{

        let likeBtn = $(btn);
        let dislikeBtn = $(btn).siblings(".dislikeButton")

        likeBtn.addClass("active")
        dislikeBtn.removeClass("active")
        let result= JSON.parse(res)
        updateLikes(likeBtn.find(".text"),result.likes)
        updateLikes(dislikeBtn.find(".text"),result.dislikes)
        
        if (result.likes<0) {
            likeBtn.removeClass("active")
            likeBtn.find("img:first").attr("src","assets/imgs/icons/thumb-up.png")
        }else{
            likeBtn.find("img:first").attr("src","assets/imgs/icons/thumb-up-active.png")
        }
        dislikeBtn.find("img:first").attr("src","assets/imgs/icons/thumb-down.png")
    }
    )
   
}

const dislikeVideo = (btn,videoId)=>{
    $.post("ajax/dislikeVideo.php",{videoId}).done(res=>{

        let dislikeBtn = $(btn);
        let likeBtn = $(btn).siblings(".likeBtn")

        dislikeBtn.addClass("active")
        likeBtn.removeClass("active")
        let result= JSON.parse(res)
        updateLikes(likeBtn.find(".text"),result.likes)
        updateLikes(dislikeBtn.find(".text"),result.dislikes)
        
        if (result.dislikes<0) {
            dislikeBtn.removeClass("active")
            dislikeBtn.find("img:first").attr("src","assets/imgs/icons/thumb-down.png")
        }else{
            dislikeBtn.find("img:first").attr("src","assets/imgs/icons/thumb-down-active.png")
        }
        likeBtn.find("img:first").attr("src","assets/imgs/icons/thumb-up.png")
    }
    )
}

const updateLikes=(el,num)=>{
    let likes = el.text() || 0;
    el.text(parseInt(likes)+parseInt(num));
}
