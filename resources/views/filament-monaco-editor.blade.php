<x-dynamic-component :component="$getFieldWrapperView()" :field="$field" class="overflow-hidden">
    <div
        x-id="['monaco-editor']"
        x-data="{
            monacoContent: $wire.$entangle(@js($getStatePath())),
            previewContent: '',
            fullScreenModeEnabled: false,
            showPreview: false,
            monacoLanguage: @js($getMonacoLanguage()),
            bladeLanguage: @js($getIsBladeLanguage()),
            showPlaceholder: @js($getShowPlaceholder()),
            monacoPlaceholder: @js($getShowPlaceholder()),
            monacoPlaceholderText: @js($getPlaceholderText()),
            monacoLoader: @js($getShowLoader()),
            monacoFontSize: @js($getFontSize()),
            lineNumbersMinChars: {{ $getLineNumbersMinChars() }},
            automaticLayout: @js($getAutomaticLayout()),
            monacoHeight: @js($getHeight()),
            monacoMinHeight: @js($getMinHeight()),
            monacoMaxHeight: @js($getMaxHeight()),
            previewHeadEndContent: @js($getPreviewHeadEndContent()),
            previewBodyAttributes: @js($getPreviewBodyAttributes()),
            previewBodyStartContent: @js($getPreviewBodyStartContent()),
            previewBodyEndContent: @js($getPreviewBodyEndContent()),
            monacoThemeDefinition: @js($getEditorTheme()),
            monacoId: $id('monaco-editor'),
            monacoTheme: '',
            editor: null,
            bladeDecorations: [],
            destroyed: false,
            originalBodyOverflowHidden: false,

            toggleFullScreenMode() {
                if (!this.fullScreenModeEnabled) {
                    this.originalBodyOverflowHidden = document.body.classList.contains('overflow-hidden');
                }

                this.fullScreenModeEnabled = !this.fullScreenModeEnabled;

                if (this.fullScreenModeEnabled) {
                    document.body.classList.add('overflow-hidden');
                } else if (!this.originalBodyOverflowHidden) {
                    document.body.classList.remove('overflow-hidden');
                }

                this.applyHeight();
            },

            applyHeight() {
                const root = this.$root;
                const fullScreen = this.fullScreenModeEnabled;

                root.style.height = fullScreen ? '100vh' : this.monacoHeight;
                root.style.minHeight = fullScreen ? '100vh' : this.monacoMinHeight;
                root.style.maxHeight = fullScreen ? '100vh' : this.monacoMaxHeight;
                root.style.width = fullScreen ? '100vw' : '';
            },

            monacoEditor(editor) {
                const element = this.$refs.monacoEditorElement;

                if (element.__fmeMonacoDisposables) {
                    element.__fmeMonacoDisposables.forEach(disposable => disposable.dispose());
                }

                const disposables = [
                    editor.onDidChangeModelContent(() => {
                        const value = editor.getValue();

                        if (this.monacoContent !== value) {
                            this.monacoContent = value;
                        }

                        this.updatePlaceholder(value);
                        this.updateBladeDecorations();
                    }),
                    editor.onDidBlurEditorWidget(() => this.updatePlaceholder(editor.getValue())),
                    editor.onDidFocusEditorWidget(() => this.updatePlaceholder(editor.getValue())),
                ];

                element.__fmeMonacoDisposables = disposables;
                element.__fmeMonacoOwner = this;
                this.bladeDecorations = element.__fmeBladeDecorations || [];
                this.updateBladeDecorations();
            },

            syncEditor(value) {
                const nextValue = value === null || value === undefined ? '' : value;

                if (this.editor && this.editor.getValue() !== nextValue) {
                    this.editor.setValue(nextValue);
                }

                this.updatePlaceholder(nextValue);
            },

            updatePlaceholder(value) {
                this.monacoPlaceholder = this.showPlaceholder && value === '';
            },

            monacoEditorFocus() {
                if (this.editor) {
                    this.editor.focus();
                }
            },

            updateBladeDecorations() {
                if (!this.bladeLanguage || !this.editor || !window.monaco) {
                    return;
                }

                const model = this.editor.getModel();

                if (!model) {
                    return;
                }

                const decorations = [];
                const directivePattern = /@[A-Za-z_][A-Za-z0-9_-]*/g;
                const source = model.getValue();
                let match;

                while ((match = directivePattern.exec(source)) !== null) {
                    const start = model.getPositionAt(match.index);
                    const end = model.getPositionAt(match.index + match[0].length);

                    decorations.push({
                        range: new window.monaco.Range(
                            start.lineNumber,
                            start.column,
                            end.lineNumber,
                            end.column
                        ),
                        options: { inlineClassName: 'fme-blade-directive' },
                    });
                }

                this.bladeDecorations = this.editor.deltaDecorations(this.bladeDecorations, decorations);
                this.$refs.monacoEditorElement.__fmeBladeDecorations = this.bladeDecorations;
            },

            wrapPreview(value) {
                return `<head>${this.previewHeadEndContent}</head>` +
                    `<body ${this.previewBodyAttributes}>` +
                    this.previewBodyStartContent +
                    value +
                    this.previewBodyEndContent +
                    // Keep the closing tag split so response middleware cannot inject into this expression.
                    '<' + '/body>';
            },

            showCodePreview() {
                this.previewContent = this.wrapPreview(
                    this.monacoContent === null || this.monacoContent === undefined ? '' : this.monacoContent
                );
                this.showPreview = true;
            },

            loadMonaco() {
                const baseUrl = 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min';

                if (window.monaco && window.monaco.editor) {
                    return Promise.resolve(window.monaco);
                }

                if (!window.__fmeMonacoPromise) {
                    window.__fmeMonacoPromise = new Promise((resolve, reject) => {
                        const timeout = setTimeout(() => reject(new Error('Timed out while loading Monaco Editor.')), 15000);
                        const resolveMonaco = monaco => {
                            clearTimeout(timeout);
                            resolve(monaco);
                        };
                        const rejectMonaco = error => {
                            clearTimeout(timeout);
                            reject(error);
                        };
                        let editorModuleRequested = false;

                        const loadEditor = () => {
                            if (editorModuleRequested) {
                                return;
                            }

                            if (window.monaco && window.monaco.editor) {
                                editorModuleRequested = true;
                                resolveMonaco(window.monaco);

                                return;
                            }

                            if (typeof window.require !== 'function' || typeof window.require.config !== 'function') {
                                rejectMonaco(new Error('Monaco Editor loader is unavailable.'));

                                return;
                            }

                            editorModuleRequested = true;
                            window.require.config({ paths: { vs: `${baseUrl}/vs` } });

                            if (!window.__fmeMonacoWorkerUrl) {
                                window.__fmeMonacoWorkerUrl = URL.createObjectURL(new Blob([
                                    `self.MonacoEnvironment = { baseUrl: '${baseUrl}' };` +
                                    `importScripts('${baseUrl}/vs/base/worker/workerMain.min.js');`,
                                ], { type: 'text/javascript' }));
                            }

                            window.MonacoEnvironment = Object.assign({}, window.MonacoEnvironment || {}, {
                                getWorkerUrl: () => window.__fmeMonacoWorkerUrl,
                            });

                            window.require(['vs/editor/editor.main'], () => resolveMonaco(window.monaco), rejectMonaco);
                        };

                        if (typeof window.require === 'function' && typeof window.require.config === 'function') {
                            loadEditor();

                            return;
                        }

                        let script = window.__fmeMonacoLoaderScript ||
                            document.querySelector('script[data-fme-monaco-loader]');

                        if (!script) {
                            for (const candidate of document.querySelectorAll('script')) {
                                if (candidate.src === `${baseUrl}/vs/loader.min.js`) {
                                    script = candidate;
                                    break;
                                }
                            }
                        }

                        if (!script) {
                            script = document.createElement('script');
                            script.src = `${baseUrl}/vs/loader.min.js`;
                            script.async = true;
                            script.dataset.fmeMonacoLoader = '';
                        }

                        window.__fmeMonacoLoaderScript = script;

                        script.addEventListener('load', loadEditor, { once: true });
                        script.addEventListener('error', () => rejectMonaco(new Error('Unable to load Monaco Editor.')), { once: true });

                        if (!script.isConnected) {
                            document.head.appendChild(script);
                        }
                    });
                }

                return window.__fmeMonacoPromise;
            },

            initialize() {
                this.monacoTheme = `fme-${this.monacoId}`;
                this.applyHeight();
                this.$watch('fullScreenModeEnabled', () => this.applyHeight());
                this.$watch('monacoContent', value => this.syncEditor(value));

                this.loadMonaco().then(monaco => {
                    if (this.destroyed || !this.$refs.monacoEditorElement) {
                        return;
                    }

                    const element = this.$refs.monacoEditorElement;
                    const existingEditor = element.__fmeMonacoEditor;

                    if (existingEditor) {
                        this.editor = existingEditor;
                    } else {
                        monaco.editor.defineTheme(this.monacoTheme, this.monacoThemeDefinition);
                        this.editor = monaco.editor.create(element, {
                            value: this.monacoContent === null || this.monacoContent === undefined ? '' : this.monacoContent,
                            theme: this.monacoTheme,
                            fontSize: Number.parseInt(this.monacoFontSize, 10) || 15,
                            lineNumbersMinChars: this.lineNumbersMinChars,
                            automaticLayout: this.automaticLayout,
                            language: this.monacoLanguage,
                        });
                        element.__fmeMonacoEditor = this.editor;
                    }

                    this.monacoEditor(this.editor);
                    this.monacoLoader = false;
                    this.syncEditor(this.monacoContent);
                }).catch(error => {
                    this.monacoLoader = false;
                    console.error(error);
                });
            },

            destroy() {
                this.destroyed = true;
                const element = this.$refs.monacoEditorElement;

                if (element && element.__fmeMonacoOwner && element.__fmeMonacoOwner !== this) {
                    return;
                }

                if (this.fullScreenModeEnabled) {
                    document.body.classList.toggle('overflow-hidden', this.originalBodyOverflowHidden);
                }

                if (!element || element.__fmeMonacoOwner !== this) {
                    return;
                }

                if (element.__fmeMonacoDisposables) {
                    element.__fmeMonacoDisposables.forEach(disposable => disposable.dispose());
                }

                if (this.editor) {
                    this.editor.deltaDecorations(this.bladeDecorations, []);
                    this.editor.dispose();
                }
                delete element.__fmeMonacoDisposables;
                delete element.__fmeMonacoEditor;
                delete element.__fmeMonacoOwner;
                delete element.__fmeBladeDecorations;
            },
        }"
        x-init="initialize()"
        :id="monacoId"
        class="fme-wrapper"
        :class="{ 'fme-full-screen': fullScreenModeEnabled }"
        x-cloak
    >
        <div class="fme-control-section">
            @if($getEnablePreview())
                <div
                    x-data="{
                        repositionTabMarker(el) {
                            this.$refs.marker.classList.remove('p-1');
                            this.$refs.marker.style.width = el.offsetWidth + 'px';
                            this.$refs.marker.style.height = el.offsetHeight + 'px';
                            this.$refs.marker.style.left = el.offsetLeft + 'px';
                        }
                    }"
                    x-cloak
                    class="fme-code-preview-tab"
                    wire:ignore
                >
                    <button type="button" @click="repositionTabMarker($el); showPreview = false;" class="fme-code-preview-tab-item">
                        {{ __('Code') }}
                    </button>
                    <button type="button" @click="repositionTabMarker($el); showCodePreview();" class="fme-code-preview-tab-item">
                        {{ __('Preview') }}
                    </button>
                    <div x-ref="marker" class="fme-code-preview-tab-marker-container p-1">
                        <div class="fme-code-preview-tab-marker"></div>
                    </div>
                </div>
            @endif

            <div class="flex items-center ml-auto">
                @if($getShowFullScreenToggle())
                    <button type="button" aria-label="{{ __('full_screen_btn_label') }}" class="fme-full-screen-btn" @click="toggleFullScreenMode()">
                        <svg class="fme-full-screen-btn-icon" x-show="!fullScreenModeEnabled" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16 4l4 0l0 4"/><path d="M14 10l6 -6"/><path d="M8 20l-4 0l0 -4"/><path d="M4 20l6 -6"/><path d="M16 20l4 0l0 -4"/><path d="M14 14l6 6"/><path d="M8 4l-4 0l0 4"/><path d="M4 4l6 6"/></svg>
                        <svg class="fme-full-screen-btn-icon" x-show="fullScreenModeEnabled" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 9l4 0l0 -4"/><path d="M3 3l6 6"/><path d="M5 15l4 0l0 4"/><path d="M3 21l6 -6"/><path d="M19 9l-4 0l0 -4"/><path d="M15 9l6 -6"/><path d="M19 15l-4 0l0 4"/><path d="M15 15l6 6"/></svg>
                    </button>
                @endif
            </div>
        </div>

        <div class="h-full w-full">
            <div class="fme-container" x-show="!showPreview">
                <div x-show="monacoLoader" class="fme-loader">
                    <svg class="fme-loader-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>

                <div x-show="!monacoLoader" class="fme-element-wrapper">
                    <div x-ref="monacoEditorElement" class="fme-element" wire:ignore style="height: 100%"></div>
                    <div x-ref="monacoPlaceholderElement" x-show="monacoPlaceholder" @click="monacoEditorFocus()" :style="'font-size: ' + monacoFontSize" class="fme-placeholder" x-text="monacoPlaceholderText"></div>
                </div>
            </div>

            <div class="fme-preview-wrapper">
                <iframe class="fme-preview" :srcdoc="previewContent" x-show="showPreview" wire:ignore></iframe>
            </div>
        </div>
    </div>
</x-dynamic-component>
