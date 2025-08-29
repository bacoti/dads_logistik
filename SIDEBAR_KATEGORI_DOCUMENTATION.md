# 📂 Sidebar Kategori System Documentation

## 🎯 Overview

Sidebar admin telah diorganisir ke dalam kategori yang dapat diklik (collapsible/expandable) untuk mengurangi kekacauan visual dan meningkatkan navigasi.

## 📋 Struktur Kategori

### **🏠 Dashboard**

-   Selalu visible, tidak dalam kategori
-   Route: `admin.dashboard`

### **📊 Data & Laporan**

-   Icon: `fa-chart-bar`
-   Default: **Expanded** (auto-detect berdasarkan route aktif)
-   Sub-menu:
    -   **Data Transaksi** (`admin.transactions.*`)
    -   **Laporan Bulanan** (`admin.monthly-reports.*`)
    -   **Laporan Kehilangan** (`admin.loss-reports.*`)

### **📋 Purchase Order**

-   Icon: `fa-clipboard-list`
-   Default: **Collapsed**
-   Sub-menu:
    -   **PO Materials** (`admin.po-materials.*`)
    -   **PO Transportasi** (`admin.po-transports.*`)
    -   **Pengajuan MFO** (`admin.mfo-requests.*`)

### **🔧 Sistem Management**

-   Icon: `fa-cogs`
-   Default: **Collapsed**
-   Sub-menu:
    -   **Master Data** (`admin.master-data.*`)
    -   **Manajemen User** (`admin.users.*`)
    -   **Manajemen Dokumen** (`admin.documents.*`)

## 🔄 Fitur Smart Auto-Expand

### **Auto-Detection Logic:**

```javascript
// Expand Data & Laporan
if (
    currentRoute.includes("/transactions") ||
    currentRoute.includes("/monthly-reports") ||
    currentRoute.includes("/loss-reports")
) {
    this.categories.reports = true;
}

// Expand Purchase Order
if (
    currentRoute.includes("/po-materials") ||
    currentRoute.includes("/po-transports") ||
    currentRoute.includes("/mfo-requests")
) {
    this.categories.po = true;
}

// Expand Sistem Management
if (
    currentRoute.includes("/master-data") ||
    currentRoute.includes("/users") ||
    currentRoute.includes("/documents")
) {
    this.categories.system = true;
}
```

## 💾 State Persistence

### **LocalStorage Integration:**

-   Status expand/collapse disimpan otomatis
-   Restore state saat page reload
-   Key: `sidebarCategories`

### **Data Structure:**

```json
{
    "reports": true,
    "po": false,
    "system": false
}
```

## 🎨 Visual Design

### **Category Headers:**

-   **Background**: Hover `bg-red-500`
-   **Icons**: Dynamic berdasarkan kategori
-   **Chevron**: `fa-chevron-up` / `fa-chevron-down`
-   **Transition**: Smooth animation

### **Sub-menu Items:**

-   **Indentation**: `ml-6` (24px from left)
-   **Size**: Smaller padding `px-3 py-2`
-   **Active State**: `bg-red-400` instead of white
-   **Icons**: Reduced size `w-4 h-4`

### **Animation Effects:**

```css
x-transition:enter="transition ease-out duration-200"
x-transition:enter-start="opacity-0 transform scale-95"
x-transition:enter-end="opacity-100 transform scale-100"
```

## 🔧 Technical Implementation

### **AlpineJS Integration:**

```javascript
x-data="sidebarCategories()"
```

### **Toggle Function:**

```javascript
toggleCategory(category) {
    this.categories[category] = !this.categories[category];
    localStorage.setItem('sidebarCategories', JSON.stringify(this.categories));
}
```

### **State Binding:**

```blade
:class="categories.reports ? 'fa-chevron-up' : 'fa-chevron-down'"
x-show="sidebarOpen && categories.reports"
```

## 📱 Responsive Behavior

### **Mobile (< 1024px):**

-   Categories hanya muncul jika sidebar terbuka
-   Touch-friendly button sizes
-   Proper overflow handling

### **Desktop (>= 1024px):**

-   Sidebar collapse/expand tetap fungsional
-   Categories tersembunyi saat sidebar mini mode
-   Smooth transitions

## 🛠️ Customization Guide

### **Menambah Kategori Baru:**

1. **Update JavaScript:**

```javascript
categories: {
    reports: true,
    po: false,
    system: false,
    newCategory: false  // ← Add here
}
```

2. **Add HTML Structure:**

```blade
<div class="space-y-1">
    <button @click="toggleCategory('newCategory')" class="...">
        <div class="flex items-center">
            <i class="fas fa-new-icon"></i>
            <span>New Category</span>
        </div>
        <i :class="categories.newCategory ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
    </button>

    <div x-show="sidebarOpen && categories.newCategory" class="ml-6 space-y-1">
        <!-- Sub-menu items -->
    </div>
</div>
```

3. **Update Auto-Detection:**

```javascript
if (currentRoute.includes("/new-route")) {
    this.categories.newCategory = true;
}
```

## 🎯 Benefits

### **User Experience:**

-   ✅ Mengurangi visual clutter
-   ✅ Navigasi lebih intuitif
-   ✅ Focus pada area kerja aktif
-   ✅ State persistence

### **Developer Experience:**

-   ✅ Mudah maintenance
-   ✅ Scalable architecture
-   ✅ Clear categorization
-   ✅ Consistent styling

### **Performance:**

-   ✅ Lazy rendering sub-menus
-   ✅ Reduced DOM complexity
-   ✅ Efficient state management

## 🚀 Future Enhancements

### **Possible Improvements:**

1. **Search dalam kategori**
2. **Drag & drop reorder categories**
3. **Custom user preferences**
4. **Category badges** (showing counts)
5. **Keyboard shortcuts** (Ctrl+1, Ctrl+2, etc.)

## 📊 Usage Analytics

### **Track Category Usage:**

```javascript
// Add tracking in toggleCategory function
gtag("event", "sidebar_category_toggle", {
    category: category,
    expanded: this.categories[category],
});
```

## 🔍 Troubleshooting

### **Category tidak expand/collapse:**

1. Check Alpine.js loaded
2. Verify `x-data` binding
3. Check console errors
4. Clear localStorage if corrupted

### **State tidak persist:**

1. Check localStorage permissions
2. Verify JSON.stringify/parse
3. Check browser storage quota

### **Animation tidak smooth:**

1. Verify Tailwind transition classes
2. Check CSS conflicts
3. Test browser compatibility

---

**✨ Hasil:** Sidebar admin yang lebih rapi, organized, dan user-friendly dengan sistem kategori yang dapat dikustomisasi!
