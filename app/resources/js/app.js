/**
 * app.js
 * 
 * Put here your application specific JavaScript implementations
 */

import './../sass/app.scss';

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.vue = new Vue({
    el: '#app',

    data: {
        lang: {
            load_more: 'Load more',
            no_more_items: 'No more items',
            photo_by: 'Photo by {name}',
        }
    },

    methods: {
        initNavbar: function() {
            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

            if ($navbarBurgers.length > 0) {
                $navbarBurgers.forEach( el => {
                    el.addEventListener('click', () => {
                        const target = el.dataset.target;
                        const $target = document.getElementById(target);
                        
                        el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');
                    });
                });
            }
        },

        ajaxRequest: function (method, url, data = {}, successfunc = function(data){}, finalfunc = function(){}, config = {})
        {
            let func = window.axios.get;
            if (method == 'post') {
                func = window.axios.post;
            } else if (method == 'patch') {
                func = window.axios.patch;
            } else if (method == 'delete') {
                func = window.axios.delete;
            }

            func(url, data, config)
                .then(function(response){
                    successfunc(response.data);
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(function(){
                        finalfunc();
                    }
                );
        },

        queryRecentPhotos: function() {
            if (window.recentPhotosPaginate === null) {
                document.getElementById('photos-recent').innerHTML = '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
            } else {
                document.getElementById('photos-recent').innerHTML += '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
            }

            if (document.getElementById('loadmore')) {
                document.getElementById('loadmore').remove();
            }

            window.vue.ajaxRequest('post', window.location.origin + '/photos/query', { paginate: window.recentPhotosPaginate }, function(response){
                if (response.code == 200) {
                    if (document.getElementById('spinner')) {
                        document.getElementById('spinner').remove();
                    }

                    if (response.data.length > 0) {
                        response.data.forEach(function(elem, index) {
                            let html = window.vue.renderPhotoPreview(elem);

                            document.getElementById('photos-recent').innerHTML += html;
                        });

                        window.recentPhotosPaginate = response.data[response.data.length - 1].id;

                        if (!response.last) {
                            document.getElementById('photos-recent').innerHTML += '<div id="loadmore"><center><a class="button is-link" href="javascript:void(0);" onclick="window.vue.queryRecentPhotos();">' + window.vue.lang.load_more + '</a></center></div>';
                        }
                    } else {
                        document.getElementById('photos-recent').innerHTML += '<div><center><br/>' + window.vue.lang.no_more_items + '</center></div>';
                    }
                }
            });
        },

        queryRandomPhotos: function() {
            document.getElementById('photos-random').innerHTML = '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';

            window.vue.ajaxRequest('post', window.location.origin + '/photos/query', { random: 1 }, function(response){
                if (response.code == 200) {
                    if (document.getElementById('spinner')) {
                        document.getElementById('spinner').remove();
                    }

                    if (response.data.length > 0) {
                        response.data.forEach(function(elem, index) {
                            let html = window.vue.renderPhotoPreview(elem);

                            document.getElementById('photos-random').innerHTML += html;
                        });
                    }
                }
            });
        },

        renderPhotoPreview: function(elem) {
            let info_by = window.vue.lang.photo_by.replace('{name}', elem.name);

            let html = `
                <a href="` + window.location.origin + '/photo/' + elem.slug + `">
                    <div class="photo" style="background-image: url('` + window.location.origin + '/img/photos/' + elem.photo_thumb + `');" onmouseover="document.getElementById('photo-overlay-` + elem.id + `').style.display = 'block';" onmouseout="document.getElementById('photo-overlay-` + elem.id + `').style.display = 'none';">
                        <div class="photo-info-overlay fade-in" id="photo-overlay-` + elem.id + `">
                            <div class="photo-info-data">
                                <div>` + info_by + `</div>
                                <div>` + elem.diffForHumans + `</div>
                            </div>
                        </div>
                    </div>
                </a>
            `;

            return html;
        },
    }
});