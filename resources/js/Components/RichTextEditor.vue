<script>
/**
 * Éditeur richtext léger basé sur Tiptap.
 * Dépendances : @tiptap/vue-3, @tiptap/starter-kit, @tiptap/extension-link
 * → npm install @tiptap/vue-3 @tiptap/starter-kit @tiptap/extension-link
 */
import { Editor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';

export default {
    components: { EditorContent },
    props: {
        modelValue: {
            type: String,
            default: '',
        },
        minHeight: {
            type: String,
            default: '140px',
        },
    },
    emits: ['update:modelValue'],
    data() {
        return {
            editor: null,
        };
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
                Link.configure({ openOnClick: false }),
            ],
            content: this.modelValue || '',
            onUpdate: ({ editor }) => {
                this.$emit('update:modelValue', editor.getHTML());
            },
        });
    },
    beforeUnmount() {
        this.editor.destroy();
    },
};
</script>

<template>
    <div class="rounded-md border border-gray-300 bg-white">
        <!-- Barre d'outils -->
        <div class="flex flex-wrap items-center gap-0.5 border-b border-gray-200 px-2 py-1.5">
            <button
                type="button"
                class="rounded p-1 text-gray-600 hover:bg-gray-100"
                :class="{ 'bg-gray-100 text-gray-900': editor?.isActive('bold') }"
                title="Gras"
                @click="editor.chain().focus().toggleBold().run()"
            >
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg>
            </button>
            <button
                type="button"
                class="rounded p-1 text-gray-600 hover:bg-gray-100"
                :class="{ 'bg-gray-100 text-gray-900': editor?.isActive('italic') }"
                title="Italique"
                @click="editor.chain().focus().toggleItalic().run()"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>
            </button>
            <div class="mx-1 h-4 w-px bg-gray-200"></div>
            <button
                type="button"
                class="rounded p-1 text-gray-600 hover:bg-gray-100"
                :class="{ 'bg-gray-100 text-gray-900': editor?.isActive('heading', { level: 2 }) }"
                title="Titre"
                @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 12h16M4 6h16M4 18h8"/></svg>
            </button>
            <button
                type="button"
                class="rounded p-1 text-gray-600 hover:bg-gray-100"
                :class="{ 'bg-gray-100 text-gray-900': editor?.isActive('bulletList') }"
                title="Liste à puces"
                @click="editor.chain().focus().toggleBulletList().run()"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="4" cy="6" r="1" fill="currentColor"/><circle cx="4" cy="12" r="1" fill="currentColor"/><circle cx="4" cy="18" r="1" fill="currentColor"/></svg>
            </button>
            <button
                type="button"
                class="rounded p-1 text-gray-600 hover:bg-gray-100"
                :class="{ 'bg-gray-100 text-gray-900': editor?.isActive('orderedList') }"
                title="Liste numérotée"
                @click="editor.chain().focus().toggleOrderedList().run()"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4M4 10h2M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>
            </button>
            <div class="mx-1 h-4 w-px bg-gray-200"></div>
            <button
                type="button"
                class="rounded p-1 text-gray-600 hover:bg-gray-100"
                title="Annuler"
                @click="editor.chain().focus().undo().run()"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 7v6h6M3.51 15a9 9 0 101.41-5.78L3 7"/></svg>
            </button>
            <button
                type="button"
                class="rounded p-1 text-gray-600 hover:bg-gray-100"
                title="Rétablir"
                @click="editor.chain().focus().redo().run()"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 7v6h-6M20.49 15a9 9 0 11-1.41-5.78L21 7"/></svg>
            </button>
        </div>

        <!-- Zone d'édition -->
        <EditorContent
            :editor="editor"
            class="prose prose-sm max-w-none px-3 py-2 text-sm text-gray-800 focus:outline-none"
            :style="`min-height: ${minHeight}`"
        />
    </div>
</template>

<style>
/* Styles appliqués à l'éditeur Tiptap */
.ProseMirror {
    outline: none;
    min-height: inherit;
}
.ProseMirror p { margin: 0 0 .5em; }
.ProseMirror h2 { font-size: 1.1rem; font-weight: 600; margin: .75em 0 .35em; }
.ProseMirror ul { list-style: disc; padding-left: 1.25rem; margin: .4em 0; }
.ProseMirror ol { list-style: decimal; padding-left: 1.25rem; margin: .4em 0; }
.ProseMirror strong { font-weight: 600; }
.ProseMirror em { font-style: italic; }
.ProseMirror a { color: #7a1f2b; text-decoration: underline; }
</style>