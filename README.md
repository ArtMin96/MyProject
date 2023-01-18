# xaxalove

<ul>
  <li>
    <input id="c1" type="checkbox">
    <label for="c1">Checkbox</label>
  </li>
  <li>
    <input id="c2" type="checkbox" checked>
    <label for="c2">Checkbox</label>
  </li>
  <li>
    <input id="r1" type="radio" name="radio" value="1">
    <label for="r1">Radio</label>
  </li>
  <li>
    <input id="r2" type="radio" name="radio" value="2" checked>
    <label for="r2">Radio</label>
  </li>
  <li>
    <input id="s1" type="checkbox" class="switch">
    <label for="s1">Switch</label>
  </li>
  <li>
    <input id="s2" type="checkbox" class="switch" checked>
    <label for="s2">Switch</label>
  </li>
</ul>

<ul>
  <li>
    <input id="c1d" type="checkbox" disabled>
    <label for="c1d">Checkbox</label>
  </li>
  <li>
    <input id="c2d" type="checkbox" checked disabled>
    <label for="c2d">Checkbox</label>
  </li>
  <li>
    <input id="r1d" type="radio" name="radiod" value="1" disabled>
    <label for="r1d">Radio</label>
  </li>
  <li>
    <input id="r2d" type="radio" name="radiod" value="2" checked disabled>
    <label for="r2d">Radio</label>
  </li>
  <li>
    <input id="s1d" type="checkbox" class="switch" disabled>
    <label for="s1d">Switch</label>
  </li>
  <li>
    <input id="s2d" type="checkbox" class="switch" checked disabled>
    <label for="s2d">Switch</label>
  </li>
</ul>



//filter ajax
<script>
  function filterProducts(filters=null,url=null) {
            if(!url) {
                url = "{{route('ajax.filter.products')}}";
            }
        $.ajax({
            url: url,
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {filters:filters},
            success: function (data) {
                $('.shop_items').html(data)
            }
        })
    }
    $(document).ready(function() {
        filterProducts();
    })
    </script>
    <script>
    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();

        $('#load a').css('color', '#dfecf6');
        $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

        var url = $(this).attr('href');  
        filterProducts(null,url);
        console.log(url);
       // window.history.pushState("", "", url);
    });
    </script>


    .env 

APP_NAME=XAXALOVE
APP_ENV=local
APP_KEY=base64:Wq+iawAF+TyRlRfwXKuJIqoXNFpFqkeodrwEhGGu7SI=
APP_DEBUG=true
APP_URL=http://xaxa.love

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pnh
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

GOOGLE_CLIENT_ID=477907760741-tcdppav3q4aola3kuoec2scpqnb5snam.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=b4oBsQ_CdJeQq_dPHIu8vfHD

GOOGLE_MAP_KEY=AIzaSyAZ-4qH0kVMk5aRbO8ulohPpANWA1iA8eA

FB_CLIENT_ID=
FB_CLIENT_SECRET=