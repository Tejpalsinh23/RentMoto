<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Faq;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminMiscController extends Controller
{
    // ==========================================
    // LOCATIONS
    // ==========================================

    public function locations()
    {
        $locations = Location::orderBy('name')->get();
        return view('admin.locations.index', compact('locations'));
    }

    public function locationStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        Location::create($request->all());
        return back()->with('success', 'Location created successfully!');
    }

    public function locationUpdate(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        $location->update($request->all());
        return back()->with('success', 'Location updated successfully!');
    }

    public function locationDestroy($id)
    {
        Location::findOrFail($id)->delete();
        return back()->with('success', 'Location deleted successfully!');
    }

    // ==========================================
    // FAQS
    // ==========================================

    public function faqs()
    {
        $faqs = Faq::orderBy('order_num')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function faqStore(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'order_num' => 'nullable|integer'
        ]);

        Faq::create($request->all());
        return back()->with('success', 'FAQ added successfully!');
    }

    public function faqUpdate(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'order_num' => 'nullable|integer'
        ]);

        $faq->update($request->all());
        return back()->with('success', 'FAQ updated successfully!');
    }

    public function faqDestroy($id)
    {
        Faq::findOrFail($id)->delete();
        return back()->with('success', 'FAQ deleted successfully!');
    }

    // ==========================================
    // CONTACT MESSAGES
    // ==========================================

    public function contacts()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.contacts.index', compact('messages'));
    }

    public function contactMarkRead($id)
    {
        ContactMessage::findOrFail($id)->update(['is_read' => true]);
        return back()->with('success', 'Message marked as read.');
    }

    public function contactReply(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);
        $request->validate([
            'reply' => 'required|string'
        ]);

        $message->update([
            'reply' => $request->reply,
            'is_read' => true
        ]);

        // Simulating email dispatch
        return back()->with('success', 'Reply recorded (simulated email dispatch to ' . $message->email . ').');
    }

    // ==========================================
    // NEWSLETTER SUBSCRIBERS
    // ==========================================

    public function newsletter()
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.newsletter.index', compact('subscribers'));
    }

    // ==========================================
    // SETTINGS
    // ==========================================

    public function settings()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::set($key, $value);
        }
        return back()->with('success', 'Settings updated successfully!');
    }

    // ==========================================
    // BLOGS
    // ==========================================

    public function blogs()
    {
        $blogs = Blog::with('category')->orderBy('created_at', 'desc')->paginate(10);
        $categories = BlogCategory::all();
        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    public function blogStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->title) . '-' . mt_rand(100, 999);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blogs', 'public');
            $data['image'] = '/storage/' . $path;
        }

        Blog::create($data);
        return back()->with('success', 'Blog post created successfully!');
    }

    public function blogUpdate(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->title) . '-' . $blog->id;

        if ($request->hasFile('image')) {
            if ($blog->image) {
                unlink(public_path($blog->image));
            }
            $path = $request->file('image')->store('blogs', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $blog->update($data);
        return back()->with('success', 'Blog post updated successfully!');
    }

    public function blogDestroy($id)
    {
        Blog::findOrFail($id)->delete();
        return back()->with('success', 'Blog post deleted successfully!');
    }
}
