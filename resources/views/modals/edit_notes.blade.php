<div>
    <div class="px-4 pb-8 text-center">
        <p class="text-center mb-2">Edit your wedding day notes:</p>
        <textarea id="notes-input" placeholder="Add a note" class="w-full rounded-lg focus:border-win-lavender focus:ring-lavender mb-2" rows="15" data-hs-textarea-auto-height="">
@if($profile->notes == null)
7:30 AM – Wake up & prepare for the arrival of hair & makeup ⛅
8:00 AM – Take 30 minutes to relax & review the plan for your BIG day! 💕
9:00 AM – Hair & makeup arrives 💄
12:00 PM – Day-of coordinator/wedding planner check-in ✅
1:00 PM – Hair & makeup complete, final touch-ups ☑️
1:30 PM – Photographer/Videographer arrives 📷🎥
2:30 PM – Get dressed (including wedding party) ✨👗
3:00 PM – Final getting ready detail pictures & portraits 📸
3:30 PM – First look with your significant other (if you choose) 💕
3:45 PM – Private vow reading (if you choose) 📖
3:45 PM – Couple’s portraits ❤️
4:15 PM – Wedding party & family formals 📷🎥
5:30 PM – Ceremony begins 💍✨
6:00 PM – Cocktail hour begins 🍸🥂
7:15 PM – Wedding party introductions 🎉
7:30 PM – Couple’s first dance & parent dances 🎵
7:45 PM – Speeches & toasts 🗒️🥂
8:00 PM – Dinner is served 🍴✨
9:00 PM – Cake cutting & dance floor opens 🎂💃🕺
11:00 PM – Wedding ends, but the memories last forever! 🎉💕
@else
{{ $profile->notes }}
@endif</textarea>
        
        <button id="save-notes-btn" type="button" class="bg-win-blue uppercase text-white text-medium px-4 py-1 rounded-lg mb-1 mt-2 float-right">
            Save
        </button>
    </div>
</div>