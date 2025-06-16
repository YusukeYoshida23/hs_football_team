const { execSync } = require('child_process');
const { spawn } = require('child_process');

console.log('Starting PHP server setup...');

try {
  // PHPをインストール
  console.log('Installing PHP...');
  execSync('apt-get update && apt-get install -y php-cli', { stdio: 'inherit' });
  
  // PHPサーバーを起動
  const port = process.env.PORT || 10000;
  console.log(`Starting PHP server on port ${port}...`);
  
  const php = spawn('php', ['-S', `0.0.0.0:${port}`], {
    stdio: 'inherit'
  });
  
  php.on('error', (err) => {
    console.error('Failed to start PHP server:', err);
  });
  
} catch (error) {
  console.error('Setup failed:', error);
  // フォールバック: 静的ファイルサーバーとして動作
  const express = require('express');
  const app = express();
  app.use(express.static('.'));
  const port = process.env.PORT || 10000;
  app.listen(port, () => {
    console.log(`Static server running on port ${port}`);
  });
}
