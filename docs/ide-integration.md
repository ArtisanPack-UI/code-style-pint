---
title: IDE Integration Guide
---

# IDE Integration Guide

This guide covers how to integrate Laravel Pint with the ArtisanPack UI code style in popular IDEs and editors.

## PhpStorm / JetBrains IDEs

### Method 1: External Tool

1. Go to **Settings/Preferences > Tools > External Tools**
2. Click the **+** button to add a new tool
3. Configure the tool:
   - **Name**: `Pint`
   - **Description**: `Format with Laravel Pint`
   - **Program**: `$ProjectFileDir$/vendor/bin/pint`
   - **Arguments**: `$FilePath$`
   - **Working directory**: `$ProjectFileDir$`
4. Click **OK** to save

**Usage**: Right-click on a file or folder > External Tools > Pint

### Method 2: File Watcher (Auto-format on Save)

1. Go to **Settings/Preferences > Tools > File Watchers**
2. Click the **+** button and select **Custom**
3. Configure the watcher:
   - **Name**: `Laravel Pint`
   - **File type**: `PHP`
   - **Scope**: `Project Files`
   - **Program**: `$ProjectFileDir$/vendor/bin/pint`
   - **Arguments**: `$FilePath$`
   - **Output paths to refresh**: `$FilePath$`
   - **Working directory**: `$ProjectFileDir$`
4. Under **Advanced Options**:
   - Uncheck "Auto-save edited files to trigger the watcher"
   - Check "Trigger the watcher on external changes"
5. Click **OK** to save

### Method 3: Keyboard Shortcut

1. Set up Pint as an External Tool (Method 1)
2. Go to **Settings/Preferences > Keymap**
3. Search for "Pint" under External Tools
4. Right-click and select **Add Keyboard Shortcut**
5. Assign your preferred shortcut (e.g., `Ctrl+Alt+L` or `Cmd+Shift+P`)

### PhpStorm Code Style Import

To align PhpStorm's built-in formatter with Pint:

1. Go to **Settings/Preferences > Editor > Code Style > PHP**
2. Click the gear icon > **Import Scheme > PHP-CS-Fixer**
3. Select your `pint.json` file

Note: This provides approximate alignment; always use Pint for final formatting.

## Visual Studio Code

### Laravel Pint Extension

1. Install the **Laravel Pint** extension from the VS Code marketplace
2. Configure in `settings.json`:

```json
{
  "laravel-pint.enable": true,
  "laravel-pint.configPath": "pint.json",
  "laravel-pint.formatOnSave": true,
  "laravel-pint.fallbackToPsr12": false
}
```

### Alternative: Run on Save Extension

1. Install the **Run on Save** extension
2. Add to `settings.json`:

```json
{
  "emeraldwalk.runonsave": {
    "commands": [
      {
        "match": "\\.php$",
        "cmd": "${workspaceFolder}/vendor/bin/pint ${file}"
      }
    ]
  }
}
```

### Format on Save with Tasks

1. Create `.vscode/tasks.json`:

```json
{
  "version": "2.0.0",
  "tasks": [
    {
      "label": "Format with Pint",
      "type": "shell",
      "command": "./vendor/bin/pint",
      "args": ["${file}"],
      "presentation": {
        "reveal": "silent"
      },
      "problemMatcher": []
    }
  ]
}
```

2. Bind to a keyboard shortcut in `keybindings.json`:

```json
{
  "key": "ctrl+shift+f",
  "command": "workbench.action.tasks.runTask",
  "args": "Format with Pint",
  "when": "editorLangId == php"
}
```

## Sublime Text

### Using Build System

1. Go to **Tools > Build System > New Build System**
2. Add the following configuration:

```json
{
  "shell_cmd": "${project_path}/vendor/bin/pint $file",
  "selector": "source.php",
  "working_dir": "${project_path}"
}
```

3. Save as `Pint.sublime-build`

**Usage**: Press `Ctrl+B` (or `Cmd+B` on Mac) to format the current file.

### Using Package: SublimeLinter-contrib-pint

1. Install via Package Control
2. Configure in Sublime settings

## Vim / Neovim

### Using ALE (Asynchronous Lint Engine)

Add to your `.vimrc` or `init.vim`:

```vim
let g:ale_fixers = {
\   'php': ['pint'],
\}

let g:ale_php_pint_executable = './vendor/bin/pint'
let g:ale_fix_on_save = 1
```

### Using null-ls.nvim (Neovim)

```lua
local null_ls = require("null-ls")

null_ls.setup({
  sources = {
    null_ls.builtins.formatting.pint.with({
      command = "./vendor/bin/pint",
    }),
  },
})
```

### Manual Command

Add to `.vimrc`:

```vim
autocmd FileType php nnoremap <buffer> <leader>f :!./vendor/bin/pint %<CR>
```

## Emacs

### Using php-cs-fixer.el

```elisp
(use-package php-cs-fixer
  :config
  (setq php-cs-fixer-command "./vendor/bin/pint"))

(add-hook 'php-mode-hook
  (lambda ()
    (add-hook 'before-save-hook 'php-cs-fixer-before-save nil t)))
```

## Git Hooks

### Pre-commit Hook

Create `.git/hooks/pre-commit`:

```bash
#!/bin/sh

# Get staged PHP files
STAGED_FILES=$(git diff --cached --name-only --diff-filter=ACM | grep -E '\.php$')

if [ -n "$STAGED_FILES" ]; then
    echo "Running Pint on staged files..."

    # Run Pint on staged files
    echo "$STAGED_FILES" | xargs ./vendor/bin/pint

    # Re-add formatted files
    echo "$STAGED_FILES" | xargs git add
fi

exit 0
```

Make it executable:

```bash
chmod +x .git/hooks/pre-commit
```

### Using Husky (npm)

1. Install Husky:

```bash
npm install husky --save-dev
npx husky install
```

2. Add pre-commit hook:

```bash
npx husky add .husky/pre-commit "./vendor/bin/pint --dirty"
```

### Using CaptainHook (Composer)

1. Install CaptainHook:

```bash
composer require --dev captainhook/captainhook
```

2. Configure `captainhook.json`:

```json
{
  "pre-commit": {
    "actions": [
      {
        "action": "./vendor/bin/pint --dirty"
      }
    ]
  }
}
```

## Troubleshooting

### Pint Not Found

Ensure Pint is installed:

```bash
composer require laravel/pint --dev
```

### Configuration Not Applied

Verify `pint.json` exists in your project root:

```bash
php artisan artisanpack:publish-pint-config
```

### Conflicts with Other Formatters

Disable other PHP formatters when using Pint:

- PhpStorm: Disable built-in PHP formatter for affected files
- VS Code: Set `"editor.formatOnSave": false` for PHP files if using Pint extension

### Slow Performance

For large files or projects:

1. Use `--dirty` flag to only format changed files
2. Exclude unnecessary directories in `pint.json`
3. Consider running Pint only on staged files via git hooks

## Related Documentation

- [Home](home)
- [Customization Guide](customization)
- [CI/CD Integration](ci-cd)
