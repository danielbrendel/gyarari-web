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
            photo_by: 'Photo by <a href="{link}">{name}</a>',
            copiedToClipboard: 'Text has been copied to clipboard!',
            reportSuccess: 'The item has been reported',
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

        setCookie: function(name, value) {
            let expDate = new Date(Date.now() + 1000 * 60 * 60 * 24 * 365);
            document.cookie = name + '=' + String(value) + '; expires=' + expDate.toUTCString() + '; path=/;';
        },

        getCookie: function(name, def = null) {
            let cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                if (cookies[i].indexOf(name) !== -1) {
                    return cookies[i].substr(cookies[i].indexOf('=') + 1);
                }
            }

            return def;
        },

        queryRecentPhotos: function(target = 'recent', search = null, loadmore = true) {
            if (window.recentPhotosPaginate === null) {
                document.getElementById('photos-' + target).innerHTML = '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
            } else {
                let oldFades = document.getElementsByClassName('photo');
                for (let i = 0; i < oldFades.length; i++) {
                    if (oldFades[i].classList.contains('fade-in')) {
                        oldFades[i].classList.remove('fade-in');
                    }
                }

                document.getElementById('photos-' + target).innerHTML += '<div id="spinner"><center><i class="fas fa-spinner fa-spin"></i></center></div>';
            }

            if (document.getElementById('loadmore')) {
                document.getElementById('loadmore').remove();
            }

            window.vue.ajaxRequest('post', window.location.origin + '/photos/query', { paginate: window.recentPhotosPaginate, search: search }, function(response){
                if (response.code == 200) {
                    if (document.getElementById('spinner')) {
                        document.getElementById('spinner').remove();
                    }

                    if (response.data.length > 0) {
                        response.data.forEach(function(elem, index) {
                            let html = window.vue.renderPhotoPreview(elem);

                            document.getElementById('photos-' + target).innerHTML += html;
                        });

                        window.recentPhotosPaginate = response.data[response.data.length - 1].id;

                        if (loadmore) {
                            if (!response.last) {
                                document.getElementById('photos-' + target).innerHTML += '<div id="loadmore"><center><a class="button is-link" href="javascript:void(0);" onclick="window.vue.queryRecentPhotos(\'' + target + '\', '  + ((search !== null) ? '\'' + search + '\'' : 'null') + ', ' + ((loadmore) ? 'true' : 'false') + ');">' + window.vue.lang.load_more + '</a></center></div>';
                            }
                        }
                    } else {
                        document.getElementById('photos-' + target).innerHTML += '<div><center><br/>' + window.vue.lang.no_more_items + '</center></div>';
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
            let info_by = window.vue.lang.photo_by.replace('{name}', elem.name).replace('{link}', window.location.origin + '/search?text=' + elem.name);

            let html = `
                <a href="` + window.location.origin + '/photo/' + elem.slug + `">
                    <div class="photo fade-in" style="background-image: url('` + window.location.origin + '/img/photos/' + elem.photo_thumb + `');" onmouseover="document.getElementById('photo-overlay-` + elem.id + `').style.display = 'block';" onmouseout="document.getElementById('photo-overlay-` + elem.id + `').style.display = 'none';">
                        <div class="photo-info-overlay fade-in" id="photo-overlay-` + elem.id + `">
                            <div class="photo-info-data">
                                <div>` + info_by + `</div>
                                <div>` + elem.diffForHumans + ` &bull; <i class="far fa-eye"></i> ` + String(elem.viewCount) + `</div>
                            </div>
                        </div>
                    </div>
                </a>
            `;
            
            return html;
        },

        performItemUpload: function(form, name, email, confirmation) {
            let elem = document.getElementById(form);
            if (elem) {
                let elConf = document.getElementById(confirmation);

                window.vue.setCookie('upload-name', document.getElementById(name).value);
                window.vue.setCookie('upload-email', document.getElementById(email).value);
                window.vue.setCookie('upload-confirmation-email', ((elConf !== null) && (elConf.checked)) ? 1 : 0);

                elem.submit();
            }
        },

        reportPhoto: function(id) {
            window.vue.ajaxRequest('get', window.location.origin + '/photo/' + id + '/report', {}, function(response) {
                if (response.code == 200) {
                    alert(window.vue.lang.reportSuccess);
                }
            });
        },

        togglePhotoOptions: function(elem) {
            if (elem.classList.contains('is-active')) {
                elem.classList.remove('is-active');
                window.optionsClickCount = 0;
            } else {
                elem.classList.add('is-active');
            }
        },

        copyToClipboard: function(text) {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert(this.lang.copiedToClipboard);
        },

        getSpinnerCode: function() {
            return `<i class="fas fa-spinner fa-spin"></i>`;
        },
    }
});