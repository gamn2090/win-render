<div id="chat-window" class="hidden fixed bottom-16 m-auto inset-y-0 inset-x-0 top-16 w-[75%] lg:w-[50%]">
    <div class="bg-white shadow-md rounded-lg w-full">
        <div class="p-4 border-b bg-blue-500 rounded-t-lg flex justify-between items-center">
            <div>
                <img src="" class="rounded-lg inline max-h-8 md:max-h-12" id="chat-image">
                <span class="subheading ml-2" id="chat-name"></span>
            </div>
            <button id="close-chat" class="text-black chat-window-btn hover:text-gray-400 focus:outline-none focus:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="chatContent" class="p-4 h-[75vh] md:h-[50vh] lg:h-[40vh] overflow-y-auto">
            <div id="loadingChat" class="min-h-60 flex flex-col rounded-xl hidden">
                <div class="flex flex-auto flex-col justify-center items-center p-4 md:p-5">
                    <div class="flex justify-center">
                        <i class="fas fa-sync-alt fa-spin text-win-purple text-lg sm:text-xl"></i>
                    </div>
                </div>
            </div>
            <!-- Chat messages will be displayed here -->
            <div id="conversationList" class="space-y-2">

            </div>
        </div>
        <div class="p-4 border-t flex">
            <div class="flex flex-row items-center h-16 rounded-xl bg-white w-full px-4">
                <div class="flex grow ml-4">
                <div class="relative w-full">
                    <input
                    id="sendMsgText" type="text"
                    class="flex w-full border rounded-full focus:outline-none focus:border-indigo-300 pl-4 h-10" autocomplete="off" placeholder="Type your message..."
                    />
                </div>
                </div>
                <div class="ml-4">
                <button
                    id="sendMessageButton" class="flex items-center justify-center bg-win-purple hover:bg-win-purple rounded-full text-white px-4 py-1 flex-shrink-0"
                >
                    <span>Send</span>
                    <span class="ml-2">
                    <svg
                        class="w-4 h-4 transform rotate-45 -mt-px"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                        ></path>
                    </svg>
                    </span>
                </button>
                </div>
            </div>
        </div>
    </div>
</div>