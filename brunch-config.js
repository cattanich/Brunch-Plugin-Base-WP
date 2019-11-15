// See http://brunch.io for documentation.

module.exports = {
    paths: {
        public: '.'
    },
    // npm: {
    //     globals: {
    //         $: 'jquery'
    //     }
    // },
    files: {
        javascripts: {
            joinTo: {
                'scripts/goquoting.js': /^app/,
                'scripts/vendorq.js': /^node_modules/
            }
        },
        stylesheets: {
            joinTo: {
                // "styles/sitio.css": /^app/,
                // "styles/vendor.css": /^(?!app)/
                "styles/goquoting.css": /sitio.scss/,
                "styles/vendorq.css": /vendor.scss/
            }
        },
    },
    modules: {
        autoRequire: {
            // outputFileName : [ entryModule ]
            'scripts/goquoting.js': ['initialize']
        }
    },
    plugins: {
        postcss: {
            options: {
                parser: require('postcss-scss'),
            },
            processors: require('autoprefixer')(['last 8 versions'])
        },
        browserSync: {
            // logLevel: "debug",
            server: false,
            proxy: "http://localhost/test",
            port: 8888
        },
        cleancss: {
            specialComments: 0,
            removeEmpty: true
        },
        // css: {
        //     modules: true
        // },
        assetsmanager: {
            copyTo: {
                // 'styles/fonts': ['node_modules/@fortawesome/fontawesome-free/webfonts/*'],
                // 'styles/fonts': ['node_modules/@mdi/font/fonts/*'],
                // 'images': ['node_modules/preloader-js/assets/images/*'],
                // 'scripts/vendor': ['node_modules/jquery/dist/jquery.js']
            }
        }
    }
};