module.exports = function(grunt) {
    grunt.initConfig({
        server: grunt.file.readJSON('server.json'),
        scp: {
            options: {
                host: '<%= server.host %>',
                username: '<%= server.username %>',
                password: '<%= server.password %>'
            },
            upload: {
                files: [{
                    cwd: 'src',
                    src: ['*.php'],
                    dest: '<%= server.path %>'
                }]
            },
        },
    });

    grunt.loadNpmTasks('grunt-scp');

    grunt.registerTask('default', 'scp');
};
