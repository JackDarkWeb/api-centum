<?php

Router::connect('post/:slug-:id', "post/read/id:([0-9]+)/slug:([a-zA-Z0-9àçéèêëíìîïñóòôöõúùûüýÿæ\-]+)");

// You can more add the Router here