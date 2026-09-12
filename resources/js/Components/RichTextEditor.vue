<script>
/**
 * Éditeur richtext basé sur Tiptap.
 * npm install @tiptap/vue-3 @tiptap/starter-kit @tiptap/extension-link @tiptap/extension-underline @tiptap/extension-placeholder @tiptap/extension-text-align
 */
import { Editor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Underline from '@tiptap/extension-underline';
import Placeholder from '@tiptap/extension-placeholder';
import TextAlign from '@tiptap/extension-text-align';

export default {
    components: { EditorContent },
    props: {
        modelValue: { type: String, default: '' },
        minHeight: { type: String, default: '140px' },
        placeholder: { type: String, default: 'Écrivez ici…' },
    },
    emits: ['update:modelValue'],
    data() {
        return { editor: null, linkUrl: '' };
    },
    watch: {
        modelValue(value) {
            if (this.editor && this.editor.getHTML() !== value) {
                this.editor.commands.setContent(value || '', false);
            }
        },
    },
    mounted() {
        this.editor = new Editor({
            extensions: [
                StarterKit,
                Link.configure({ openOnClick: false, HTMLAttributes: { class: 'text-primary-600 underline' } }),
                Underline,
                Placeholder.configure({ placeholder: this.placeholder }),
                TextAlign.configure({ types: ['heading', 'paragraph'] }),
            ],
            content: this.modelValue || '',
            onUpdate: ({ editor }) => {
                this.$emit('update:modelValue', editor.getHTML());
            },
        });
    },
    beforeUnmount() {
        this.editor?.destroy();
    },
    methods: {
        setLink() {
            const url = window.prompt('URL du lien :', this.editor.getAttributes('link').href || 'https://');
            if (url === null) return;
            if (url === '') { this.editor.chain().focus().extendMarkRange('link').unsetLink().run(); return; }
            this.editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
        },
        btn(active) {
            return active
                ? 'rounded p-1.5 text-primary-700 bg-primary-50'
                : 'rounded p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700';
        },
    },
};
</script>

<template>
    <div class="rounded-lg border border-gray-300 bg-white overflow-hidden">
        <!-- Toolbar -->
        <div v-if="editor" class="flex flex-wrap items-center gap-0.5 border-b border-gray-200 px-2 py-1.5 bg-gray-50/50">
            <!-- Text style -->
            <button type="button" :class="btn(editor.isActive('bold'))" title="Gras" @click="editor.chain().focus().toggleBold().run()">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg>
            </button>
            <button type="button" :class="btn(editor.isActive('italic'))" title="Italique" @click="editor.chain().focus().toggleItalic().run()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>
            </button>
            <button type="button" :class="btn(editor.isActive('underline'))" title="Souligné" @click="editor.chain().focus().toggleUnderline().run()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 3v7a6 6 0 006 6 6 6 0 006-6V3M4 21h16"/></svg>
            </button>
            <button type="button" :class="btn(editor.isActive('strike'))" title="Barré" @click="editor.chain().focus().toggleStrike().run()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 4H9a3 3 0 00-1 5.83M4 12h16M8 20h7a3 3 0 001-5.83"/></svg>
            </button>

            <div class="mx-1 h-4 w-px bg-gray-200"></div>

            <!-- Headings -->
            <button type="button" :class="btn(editor.isActive('heading', { level: 2 }))" title="Titre" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6V18M4 12H14M14 6V18"/><text x="18" y="18" font-size="10" fill="currentColor" stroke="none" font-weight="700">2</text></svg>
            </button>
            <button type="button" :class="btn(editor.isActive('heading', { level: 3 }))" title="Sous-titre" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6V18M4 12H14M14 6V18"/><text x="18" y="18" font-size="10" fill="currentColor" stroke="none" font-weight="700">3</text></svg>
            </button>

            <div class="mx-1 h-4 w-px bg-gray-200"></div>

            <!-- Lists -->
            <button type="button" :class="btn(editor.isActive('bulletList'))" title="Liste à puces" @click="editor.chain().focus().toggleBulletList().run()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="4" cy="6" r="1" fill="currentColor"/><circle cx="4" cy="12" r="1" fill="currentColor"/><circle cx="4" cy="18" r="1" fill="currentColor"/></svg>
            </button>
            <button type="button" :class="btn(editor.isActive('orderedList'))" title="Liste numérotée" @click="editor.chain().focus().toggleOrderedList().run()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4M4 10h2M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>
            </button>

            <div class="mx-1 h-4 w-px bg-gray-200"></div>

            <!-- Block -->
            <button type="button" :class="btn(editor.isActive('blockquote'))" title="Citation" @click="editor.chain().focus().toggleBlockquote().run()">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"/></svg>
            </button>
            <button type="button" class="rounded p-1.5 text-gray-500 hover:bg-gray-100" title="Séparateur" @click="editor.chain().focus().setHorizontalRule().run()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/></svg>
            </button>

            <div class="mx-1 h-4 w-px bg-gray-200"></div>

            <!-- Link -->
            <button type="button" :class="btn(editor.isActive('link'))" title="Lien" @click="setLink">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101M10.172 13.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            </button>
            <button v-if="editor.isActive('link')" type="button" class="rounded p-1.5 text-red-500 hover:bg-red-50" title="Retirer le lien" @click="editor.chain().focus().unsetLink().run()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>

            <div class="mx-1 h-4 w-px bg-gray-200"></div>

            <!-- Align -->
            <button type="button" :class="btn(editor.isActive({ textAlign: 'left' }))" title="Aligner à gauche" @click="editor.chain().focus().setTextAlign('left').run()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M3 12h12M3 18h18"/></svg>
            </button>
            <button type="button" :class="btn(editor.isActive({ textAlign: 'center' }))" title="Centrer" @click="editor.chain().focus().setTextAlign('center').run()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M6 12h12M3 18h18"/></svg>
            </button>

            <div class="flex-1"></div>

            <!-- Undo/Redo -->
            <button type="button" class="rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="Annuler" @click="editor.chain().focus().undo().run()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7v6h6M3.51 15a9 9 0 101.41-5.78L3 7"/></svg>
            </button>
            <button type="button" class="rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="Rétablir" @click="editor.chain().focus().redo().run()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 7v6h-6M20.49 15a9 9 0 11-1.41-5.78L21 7"/></svg>
            </button>
        </div>

        <!-- Editor -->
        <EditorContent :editor="editor" class="prose prose-sm max-w-none px-4 py-3 text-sm text-gray-800" :style="`min-height: ${minHeight}`" />
    </div>
</template>

<style>
.ProseMirror { outline: none; min-height: inherit; }
.ProseMirror p { margin: 0 0 .5em; }
.ProseMirror h2 { font-size: 1.15rem; font-weight: 700; margin: .75em 0 .35em; color: #1f2937; }
.ProseMirror h3 { font-size: 1rem; font-weight: 600; margin: .6em 0 .3em; color: #374151; }
.ProseMirror ul { list-style: disc; padding-left: 1.25rem; margin: .4em 0; }
.ProseMirror ol { list-style: decimal; padding-left: 1.25rem; margin: .4em 0; }
.ProseMirror strong { font-weight: 700; }
.ProseMirror em { font-style: italic; }
.ProseMirror u { text-decoration: underline; }
.ProseMirror s { text-decoration: line-through; color: #9ca3af; }
.ProseMirror a { color: #7a1f2b; text-decoration: underline; }
.ProseMirror blockquote { border-left: 3px solid #7a1f2b; padding-left: 1rem; margin: .6em 0; color: #6b7280; font-style: italic; }
.ProseMirror hr { border: none; border-top: 1px solid #e5e7eb; margin: 1em 0; }
.ProseMirror p.is-editor-empty:first-child::before { content: attr(data-placeholder); color: #9ca3af; pointer-events: none; float: left; height: 0; }
</style>
