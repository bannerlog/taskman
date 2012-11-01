#!/usr/bin/env php
<?php

$pharFile = 'taskman.phar';

if (file_exists($pharFile)) {
    unlink($pharFile);
}

$p = new Phar($pharFile, 0, 'taskman.phar');
$p->setSignatureAlgorithm(Phar::SHA1);

$stub = <<<'EOF'
#!/usr/bin/env php
<?php

Phar::mapPhar('taskman.phar');
require 'phar://taskman.phar/bin/taskman.php';

__HALT_COMPILER();
EOF;

$p->startBuffering();

$content = file_get_contents(__DIR__.'/taskman.php');
$content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
$p->addFromString('bin/taskman.php', $content);

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../src'));
while ($it->valid()) {
    if (! $it->isDot()) {
        $p->addFromString(
            'src/' . $it->getSubPathName(),
            file_get_contents($it->key())
        );
    }

    $it->next();
}

$p->setStub($stub);
$p->stopBuffering();
$p->compressFiles(Phar::GZ);
