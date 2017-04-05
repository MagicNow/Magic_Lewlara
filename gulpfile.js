var elixir = require('laravel-elixir'),
    gulp = require('gulp'),
    less = require('gulp-less'),

    lessPath = './resources/assets/less/';
    bloglessPath = './resources/assets/blog/less/';

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */


gulp.task('less', function () {
  return gulp.src([
        lessPath + 'geral.less',
        lessPath + 'paginas/textarea-editavel.less',
        lessPath + 'email/newsletter.less',
        lessPath + 'pdf/pdf_post.less'
    ])
    .pipe(less())
    .pipe(gulp.dest('./resources/assets/css/'));
});

gulp.task('blogless', function () {
  return gulp.src([
        bloglessPath + 'blog.less'
    ])
    .pipe(less())
    .pipe(gulp.dest('./resources/assets/blog/css/'));
});

elixir.config.sourcemaps = false;

elixir(function(mix) {  
    // compila de resources/assets/  em resources/assets/css
    
    mix.task('less');
    mix.task('blogless');

    // mescla arquivos descritos que estão em public/css para o arquivo public/css/all.css
    mix.styles([
        "normalize.css",
        "libs/datepicker.css",
        "libs/select2.min.css",
        "geral.css",
        "../blog/css/blog.css"
    ], 'public/css/all.css', 'resources/assets/css');


    // 'compila' (copia) css separado da página do iframe dos textarea editavel
    mix.styles([
            "textarea-editavel.css",
        ], 'public/css/textarea-editavel.css','resources/assets/css');
    mix.styles([
            "newsletter.css",
        ], 'public/css/email_newsletter.css','resources/assets/css');
    mix.styles([
            "pdf_post.css",
        ], 'public/css/pdf_post.css','resources/assets/css');

    // mescla arquivos descritos que estão em resources/assets/js para o arquivo public/js/all.js
    mix.scripts([
            "libs/jquery/jquery.2.1.3.min.js",
            "libs/bootstrap/bootstrap.3.3.1.min.js",
            "libs/bootstrap/bootstrap-datepicker.js",
            "libs/select2/select2.min.js",
            "libs/select2/i18n/pt-BR.js",
            "app.js",
        ],'public/js/all.js','resources/assets/js')
    // scripts de cada page pagina
    .scripts([
            "pages/perfil.js"
        ],'public/js/pages/perfil.js','resources/assets/js')
        .scripts([
            "pages/cliente.js"
        ],'public/js/pages/cliente.js','resources/assets/js')
        .scripts([
            "pages/usuario.js"
        ],'public/js/pages/usuario.js','resources/assets/js')
        .scripts([
            "pages/post.js"
        ],'public/js/pages/post.js','resources/assets/js')
        .scripts([
            "pages/post_todos.js"
        ],'public/js/pages/post_todos.js','resources/assets/js')
        .scripts([
            "pages/newsletter_nova.js"
        ],'public/js/pages/newsletter_nova.js','resources/assets/js')
        .scripts([
            "pages/newsletter_lista.js"
        ],'public/js/pages/newsletter_lista.js','resources/assets/js')
        .scripts([
            "pages/post_gerarpdf.js"
        ],'public/js/pages/post_gerarpdf.js','resources/assets/js')
        .scripts([
            "../blog/js/blog.js"
        ],'public/blogdir/js/blog.js','resources/assets/js')
        .scripts([
            "pages/comentario.js"
        ],'public/js/pages/comentario.js','resources/assets/js');
    
    mix.copy('resources/assets/js/libs/AjaxFileUpload','public/libs/AjaxFileUpload');
    mix.copy('resources/assets/js/libs/jwysiwyg','public/libs/jwysiwyg');
    mix.copy('resources/assets/js/libs/jscrollpane','public/libs/jscrollpane');
    mix.copy('resources/assets/js/libs/dropzone','public/libs/dropzone');
    mix.copy('resources/assets/js/libs/lightslider','public/libs/lightslider');
    mix.copy('resources/assets/js/libs/redactor','public/libs/redactor');
    mix.copy('resources/assets/js/blog/libs/TagCanvas','public/libs/TagCanvas');

    
    

    // build de versionamento
    mix.version([
                'css/all.css', 
                'js/all.js', 
                'js/pages/cliente.js', 
                'js/pages/perfil.js', 
                'js/pages/post.js', 
                'js/pages/post_todos.js', 
                'js/pages/newsletter_nova.js',
                'js/pages/newsletter_lista.js',
                'js/pages/post_gerarpdf.js',
                'js/pages/usuario.js',
                'blogdir/js/blog.js',
                'js/pages/comentario.js'
                ]);

});

elixir.config.registerWatcher("less", "./resources/assets/less/**");
elixir.config.registerWatcher("blogless", "./resources/assets/blog/less/**");
