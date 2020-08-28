<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title></title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />

        <!-- PLUGINS CSS STYLE -->
        @set(file.css.data=[url|lib/bootstrap4/css/bootstrap.min.css])end
        @set(file.css.data=[url|lib/font-awesome/css/font-awesome.min.css])end
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
        @set(file.css.data=[url|lib/freelancer/css/freelancer.min.css])end        
        @set(file.css.data=[url|lib/website/css/style.css])end

        @set(file.script.data=[url|<?= UrlDirectory::getDomin() ?>lib/jquery/js/jquery.min.js])end

        <meta name="HandheldFriendly" content="true" />
        <meta name="MobileOptimized" content="320" />
        <meta name="Viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />


        @cssPut()end
    </head>
    <body>

        @set(view.data=[type|template,objectName|board,db|false,file|nav])end

        @set(view.data=[type|template,objectName|board,db|false,file|header])end

        @section(view.data=[type|template,name|content])end

        @set(view.data=[type|template,objectName|board,db|false,file|footer])end

        @set(file.script.data=[url|lib/jquery/js/jquery-migrate-3.0.0.min.js])end
        @set(file.script.data=[url|lib/popper/js/popper.min.js])end
        @set(file.script.data=[url|lib/bootstrap4/js/bootstrap.min.js])end
        @set(file.script.data=[url|lib/jquery-easing/js/jquery.easing.min.js])end
        @set(file.script.data=[url|lib/freelancer/js/freelancer.js])end
    </body>
</html>