define(['/static/libs/swiper3.1.0/swiper3.1.0.min.js'],function(Swiper) {
        return {
            instant: function() {
                var containerArr = document.getElementsByClassName('swiper-container');
                if (containerArr.length>0) {
                    for (var i = 0; i<containerArr.length; i++) {
                        var classVal = 'swiper-container swiper-container'+(i+1);
                        var childClass= 'swiper-pagination swiper-pagination'+(i+1);
                        containerArr[i].children[1].setAttribute('class',childClass);
                        containerArr[i].setAttribute('class',classVal);

                        if (containerArr[i].children[0].children.length>1) {
                            new Swiper('.swiper-container'+(i+1), {
                                pagination : '.swiper-pagination'+(i+1),
                                paginationClickable :true,
                                autoplay: 5000
                            })
                        } else {
                            containerArr[i].children[1].style.display = 'none';
                            new Swiper('.swiper-container'+(i+1), {
                                loop: false
                            })
                        }
                    }
                }
                var swiper_little_obj = document.getElementsByClassName('swiper-little-container');
                if (swiper_little_obj.length>0) {
                    for (var i = 0; i<swiper_little_obj.length; i++) {
                        var classVal = 'swiper-little-container swiper-little-container'+(i+1);
                        swiper_little_obj[i].setAttribute('class',classVal);

                        new Swiper('.swiper-little-container'+(i+1), {
                            slidesPerView: 5
                        });
                    }
                }
            },
        };
    }
);