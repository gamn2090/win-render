<?php

namespace App\Support;

class WinTimelineToolHtmlPatcher
{
    public static function apply(string $html, ?string $displayName = null): string
    {
        $html = str_replace(["\r\n", "\r"], "\n", $html);
        $html = self::applyHeaderPatch($html, $displayName);
        $html = self::applyBlankDefaultStatePatch($html);

        $html = str_replace(
            '        opt.textContent = "Add: " + cat',
            '        opt.textContent = cat',
            $html
        );

        $html = str_replace(
            '    function populateTaskPreset(block){
      els.taskPreset.innerHTML = ""
      let category = "Other"
      if(block.vendorId !== KEY_VENDOR_ID) category = getVendorById(block.vendorId)?.category || "Other"
      const presets = state.taskPresetsByCategory[category] || state.taskPresetsByCategory["Other"]
      for(const p of presets) {
        const opt = document.createElement("option")
        opt.value = p
        opt.textContent = p
        els.taskPreset.appendChild(opt)
      }
      const optCustom = document.createElement("option")
      optCustom.value="__custom__"
      optCustom.textContent="Custom"
      els.taskPreset.appendChild(optCustom)
      els.taskPreset.value = presets[0] || "__custom__"
    }',
            '    function populateTaskPreset(block){
      els.taskPreset.innerHTML = ""
      const opt = document.createElement("option")
      opt.value = "Confirm Details"
      opt.textContent = "Confirm Details"
      els.taskPreset.appendChild(opt)
      els.taskPreset.value = "Confirm Details"
    }',
            $html
        );

        $runtimePatch = <<<'JS'
    function setupListHost(panelEl, listId){
      if(!panelEl) return null
      let host = panelEl.querySelector("#" + listId)
      if(!host){
        host = document.createElement("div")
        host.id = listId
        panelEl.appendChild(host)
      }
      return host
    }

    function renderSetupCards(){
      const keyPanel = document.getElementById("addKeyEventBtn")?.closest(".tasksPanel")
      const vendorPanel = document.getElementById("addVendorBlockBtn")?.closest(".tasksPanel")
      renderSetupCardList(setupListHost(keyPanel, "keyEventSetupCards"), state.blocks.filter(b => b.vendorId === KEY_VENDOR_ID), true)
      renderSetupCardList(setupListHost(vendorPanel, "vendorBlockSetupCards"), state.blocks.filter(b => b.vendorId !== KEY_VENDOR_ID), false)
    }

    function captureKeyEventSetupDrafts(host, blocks){
      if(!host) return
      host.querySelectorAll(".taskRow[data-block-id]").forEach(row => {
        const id = row.dataset.blockId
        const b = blocks.find(x => x.id === id)
        if(!b) return
        const selects = row.querySelectorAll("select")
        if(selects.length < 3) return
        b.eventName = selects[0].value
        const pr = state.keyEventPresets.find(p => p.label === b.eventName)
        b.icon = pr?.icon || b.icon || "⭐"
        const st = parseTime(selects[1].value)
        const en = parseTime(selects[2].value)
        if(st !== null){
          b.startMin = clamp(roundToSnap(st), state.dayStartMin, state.dayEndMin - state.snapMin)
        }
        if(en !== null){
          const lo = b.startMin + state.snapMin
          b.endMin = clamp(roundToSnap(en), lo, state.dayEndMin)
        }
        if(b.endMin <= b.startMin) b.endMin = Math.min(state.dayEndMin, b.startMin + state.snapMin)
      })
    }

    function captureVendorBlockSetupDrafts(host, blocks){
      if(!host) return
      host.querySelectorAll(".taskRow[data-block-id]").forEach(row => {
        const id = row.dataset.blockId
        const b = blocks.find(x => x.id === id)
        if(!b) return
        const selects = row.querySelectorAll("select")
        if(selects.length < 3) return
        b.vendorId = selects[0].value
        delete b.eventName
        delete b.icon
        const st = parseTime(selects[1].value)
        const en = parseTime(selects[2].value)
        if(st !== null){
          b.startMin = clamp(roundToSnap(st), state.dayStartMin, state.dayEndMin - state.snapMin)
        }
        if(en !== null){
          const lo = b.startMin + state.snapMin
          b.endMin = clamp(roundToSnap(en), lo, state.dayEndMin)
        }
        if(b.endMin <= b.startMin) b.endMin = Math.min(state.dayEndMin, b.startMin + state.snapMin)
      })
    }

    function renderSetupCardList(host, blocks, isKeyEvent){
      if(!host) return
      if(isKeyEvent) captureKeyEventSetupDrafts(host, blocks)
      else captureVendorBlockSetupDrafts(host, blocks)
      host.innerHTML = ""
      for(const block of blocks){
        const fieldsRow = document.createElement("div")
        fieldsRow.className = "taskRow"
        fieldsRow.dataset.blockId = block.id

        const nameField = document.createElement("select")
        if(isKeyEvent){
          for(const preset of state.keyEventPresets){
            const takenElsewhere = blocks.some(x => x.id !== block.id && x.eventName === preset.label)
            if(takenElsewhere && preset.label !== (block.eventName || "")) continue
            const opt = document.createElement("option")
            opt.value = preset.label
            opt.textContent = preset.label
            if((block.eventName || "") === preset.label) opt.selected = true
            nameField.appendChild(opt)
          }
          if(!nameField.options.length && (block.eventName || "")){
            const opt = document.createElement("option")
            opt.value = block.eventName
            opt.textContent = block.eventName
            opt.selected = true
            nameField.appendChild(opt)
          }
        } else {
          for(const vendor of state.vendors){
            const takenElsewhere = blocks.some(x => x.id !== block.id && x.vendorId === vendor.id)
            if(takenElsewhere && vendor.id !== block.vendorId) continue
            const opt = document.createElement("option")
            opt.value = vendor.id
            opt.textContent = vendor.category + " • " + vendor.name
            if(block.vendorId === vendor.id) opt.selected = true
            nameField.appendChild(opt)
          }
          if(!nameField.options.length && block.vendorId){
            const v = state.vendors.find(x => x.id === block.vendorId)
            if(v){
              const opt = document.createElement("option")
              opt.value = v.id
              opt.textContent = v.category + " • " + v.name
              opt.selected = true
              nameField.appendChild(opt)
            }
          }
        }

        const startField = document.createElement("select")
        const endField = document.createElement("select")
        buildTimeOptions(startField, state.dayStartMin, state.dayEndMin, state.snapMin)
        buildTimeOptions(endField, state.dayStartMin, state.dayEndMin, state.snapMin)
        startField.value = formatTime(block.startMin)
        endField.value = formatTime(block.endMin)

        const actions = document.createElement("div")
        actions.className = "setupCardActions"

        const saveBtn = document.createElement("button")
        saveBtn.type = "button"
        saveBtn.className = "btn accent"
        saveBtn.textContent = "Save"
        const deleteBtn = document.createElement("button")
        deleteBtn.type = "button"
        deleteBtn.className = "btn"
        deleteBtn.textContent = "Delete"

        saveBtn.addEventListener("click", () => {
          const startParsed = parseTime(startField.value)
          const endParsed = parseTime(endField.value)
          if(startParsed === null || endParsed === null) return showToast("Use time format like 4:00 PM")
          let nextStart = roundToSnap(startParsed)
          let nextEnd = roundToSnap(endParsed)
          if(nextEnd <= nextStart) return showToast("End must be after start")
          nextStart = clamp(nextStart, state.dayStartMin, state.dayEndMin - state.snapMin)
          nextEnd = clamp(nextEnd, nextStart + state.snapMin, state.dayEndMin)
          block.startMin = nextStart
          block.endMin = nextEnd
          if(isKeyEvent){
            const nextName = nameField.value
            const dup = state.blocks.some(b =>
              b.vendorId === KEY_VENDOR_ID && b.id !== block.id && b.eventName === nextName
            )
            if(dup) return showToast("That key event is already used — pick another")
            const preset = state.keyEventPresets.find(p => p.label === nextName)
            block.eventName = nextName
            block.icon = preset?.icon || "⭐"
            block.vendorId = KEY_VENDOR_ID
          } else {
            const nextVid = nameField.value
            const dupV = state.blocks.some(b => b.vendorId !== KEY_VENDOR_ID && b.id !== block.id && b.vendorId === nextVid)
            if(dupV) return showToast("That vendor is already on the timeline — pick another")
            block.vendorId = nextVid
            delete block.eventName
            delete block.icon
          }
          block.tasks = (Array.isArray(block.tasks) ? block.tasks : []).filter(t => t.atMin >= block.startMin && t.atMin <= block.endMin)
          renderAll()
          showToast("Saved")
        })

        deleteBtn.addEventListener("click", () => {
          state.blocks = state.blocks.filter(x => x.id !== block.id)
          renderAll()
          showToast("Deleted")
        })

        actions.appendChild(saveBtn)
        actions.appendChild(deleteBtn)

        fieldsRow.appendChild(nameField)
        fieldsRow.appendChild(startField)
        fieldsRow.appendChild(endField)
        host.appendChild(fieldsRow)
        host.appendChild(actions)
      }
    }

    const __renderAllOriginal = renderAll
    renderAll = function(){
      __renderAllOriginal()
      renderSetupCards()
    }

    renderKeyEventTimeline = function(rowEl){
      rowEl.querySelectorAll(".keCenterRail,.keDot,.keAboveWrap,.keBelowWrap").forEach(el => el.remove())

      const total = totalWindow()
      if(total <= 0) return

      const blocks = state.blocks
        .filter(b => b.vendorId === KEY_VENDOR_ID
                  && b.endMin > state.dayStartMin
                  && b.startMin < state.dayEndMin)
        .sort((a,b) => a.startMin - b.startMin)

      const rail = document.createElement("div")
      rail.className = "keCenterRail"
      rowEl.appendChild(rail)

      const rowWidth = rowEl.clientWidth || rowEl.getBoundingClientRect().width || 1
      const cardWidthPct = Math.max(12, (152 / rowWidth) * 100)
      const minGapPct = Math.max(4, cardWidthPct * 0.18)
      const placements = []
      const laneEnds = []

      blocks.forEach((b) => {
        const midMin = (b.startMin + b.endMin) / 2
        const anchorPct = clamp(((midMin - state.dayStartMin) / total) * 100, 4, 96)
        let lane = 0
        let leftPct = anchorPct

        while (lane < 8) {
          const endPct = leftPct + cardWidthPct
          const overlaps = laneEnds[lane] && laneEnds[lane].some((slot) => !(endPct + minGapPct <= slot.start || leftPct >= slot.end + minGapPct))
          if (!overlaps) break
          lane += 1
          leftPct = clamp(anchorPct + (lane % 2 === 0 ? 1 : -1) * minGapPct * lane, 4, 96 - cardWidthPct)
        }

        if (!laneEnds[lane]) laneEnds[lane] = []
        laneEnds[lane].push({ start: leftPct, end: leftPct + cardWidthPct })
        placements.push({ block: b, leftPct, lane })
      })

      placements.forEach(({ block: b, leftPct, lane }) => {
        const isAbove = (lane % 2 === 0)
        const stackOffset = 0

        const dot = document.createElement("div")
        dot.className = "keDot"
        dot.style.left = leftPct + "%"
        rowEl.appendChild(dot)

        const wrap = document.createElement("div")
        wrap.className = isAbove ? "keAboveWrap" : "keBelowWrap"
        wrap.style.left = leftPct + "%"
        wrap.style.width = "140px"
        if(isAbove){
          wrap.style.top = (8 + stackOffset) + "px"
        } else {
          wrap.style.bottom = (8 + stackOffset) + "px"
        }

        const leader = document.createElement("div")
        leader.className = "keLeader"

        const card = document.createElement("div")
        card.className = "keCard"
        card.innerHTML = `
          <div class="keCardHead">
            <div class="keCardIcon" aria-hidden="true">${svgIconForBlock(b)}</div>
            <p class="keCardTitle">${escapeHtml(blockTitle(b))}</p>
          </div>
          <p class="keCardTime">${escapeHtml(formatTime(b.startMin))} – ${escapeHtml(formatTime(b.endMin))}</p>
        `
        card.addEventListener("click", () => openEditor(b.id))

        if(isAbove){
          wrap.appendChild(card)
          wrap.appendChild(leader)
        } else {
          wrap.appendChild(leader)
          wrap.appendChild(card)
        }

        rowEl.appendChild(wrap)
      })
    }

    function syncTaskTimeOptions(){
      if(!els.taskTime) return
      const startParsed = parseTime(els.editStart.value)
      const endParsed = parseTime(els.editEnd.value)
      if(startParsed === null || endParsed === null || endParsed < startParsed){
        els.taskTime.innerHTML = ""
        els.taskTime.disabled = true
        return
      }
      els.taskTime.disabled = false
      buildTimeOptions(
        els.taskTime,
        roundToSnap(startParsed),
        roundToSnap(endParsed),
        state.snapMin
      )
    }

    const __openEditorOriginal = openEditor
    openEditor = function(blockId){
      __openEditorOriginal(blockId)
      syncTaskTimeOptions()
    }

    addTaskToEditingBlock = function(){
      const b = state.blocks.find(x => x.id === state.editingBlockId)
      if(!b) return
      let title = els.taskPreset.value
      if(title === "__custom__") title = prompt("Enter custom task name") || ""
      title = (title || "").trim()
      const startParsed = parseTime(els.editStart.value)
      const endParsed = parseTime(els.editEnd.value)
      if(startParsed === null || endParsed === null || endParsed < startParsed) return showToast("Choose valid start and end times")
      if(!title) return showToast("Enter a task name")
      if(!els.taskTime.value) return showToast("Choose a time")
      const timeParsed = parseTime(els.taskTime.value)
      if(timeParsed === null) return showToast("Choose a time")
      const startMin = roundToSnap(startParsed)
      const endMin = roundToSnap(endParsed)
      const atMin = roundToSnap(timeParsed)
      if(atMin < startMin || atMin > endMin) return showToast("Task time must be within the block")
      b.tasks = Array.isArray(b.tasks) ? [...b.tasks, { id: uid("t"), title, atMin }] : [{ id: uid("t"), title, atMin }]
      renderTaskList()
      renderAll()
      showToast("Task added")
      syncTaskTimeOptions()
    }

    els.editStart.addEventListener("change", syncTaskTimeOptions)
    els.editEnd.addEventListener("change", syncTaskTimeOptions)

JS;

        $drawerCss = <<<'HTML'
<style>
  #taskPreset,#vendorBlockVendor,#keyEventPreset,#editBlockType,.tasksPanel select,.taskRow select{width:100%; min-width:0; white-space:normal; text-overflow:clip}
  #taskPreset option,#vendorBlockVendor option,#keyEventPreset option,.tasksPanel select option,.taskRow select option{white-space:normal}
  .setupCardActions{display:grid; grid-template-columns:1.4fr 140px 110px; gap:10px; margin-top:10px; align-items:center}
  .setupCardActions .btn.accent{grid-column:2; justify-self:stretch}
  .setupCardActions .btn:not(.accent){grid-column:3; justify-self:stretch}
  .layout{align-items:stretch}
  .setupCard{display:flex; flex-direction:column; max-height:calc(100vh - 12rem); min-height:0}
  .setupCard .cardBody{overflow-y:auto; overflow-x:hidden; min-height:0; flex:1 1 auto; overscroll-behavior:contain; scrollbar-gutter:stable; -webkit-overflow-scrolling:touch}
  .setupCard .cardBody::-webkit-scrollbar{width:8px}
  .setupCard .cardBody::-webkit-scrollbar-thumb{background:rgba(21,21,21,.18); border-radius:999px}
  .setupCard .cardBody::-webkit-scrollbar-track{background:transparent}
  @media (max-width:1040px){
    .setupCard{max-height:min(70vh, calc(100vh - 10rem))}
  }
  #vendorRows.rows{gap:8px}
  #overviewRows.rows{gap:10px}
  #vendorRows .rowRight,
  #overviewRows .rowRight:not(.keTimeline){
    min-height:84px;
  }
  #vendorRows .rowLeft,
  #overviewRows .row:has(> .rowRight:not(.keTimeline)) .rowLeft{
    padding:8px 10px;
  }
  #vendorRows .vendorCat,
  #overviewRows .row:has(> .rowRight:not(.keTimeline)) .vendorCat{
    margin-bottom:4px;
  }
  @media print{
    #vendorRows .rowRight,
    #overviewRows .rowRight:not(.keTimeline){min-height:80px !important}
  }
</style>
HTML;

        $html = preg_replace('/<\/head>/', $drawerCss . '</head>', $html, 1) ?? $html;

        $html = str_replace(
            <<<'JS'
      const LANE_H   = compact ? 72 : 92   // px per vertical lane (taller for narrow wrapped text)
      const TOP_BASE = compact ? 8  : 10   // px from top of row to first lane
      const BLOCK_H  = compact ? 60 : 72   // block element height

      // Clear existing blocks
JS,
            <<<'JS'
      const vendorSurface = containerEl && (containerEl.id === "vendorRows" || containerEl.id === "overviewRows")
      const LANE_H   = compact ? 72 : (vendorSurface ? 76 : 92)   // px per vertical lane (taller for narrow wrapped text)
      const TOP_BASE = compact ? 8  : (vendorSurface ? 8 : 10)   // px from top of row to first lane
      const BLOCK_H  = compact ? 60 : (vendorSurface ? 60 : 72)   // block element height
      const ROW_PAD  = compact ? 16 : (vendorSurface ? 12 : 16)

      // Clear existing blocks
JS,
            $html
        );

        $html = str_replace(
            '        rowEl.style.minHeight = (TOP_BASE + info.numLanes * LANE_H + 16) + "px"',
            '        rowEl.style.minHeight = (TOP_BASE + info.numLanes * LANE_H + ROW_PAD) + "px"',
            $html
        );

        $html = str_replace(
            '<input id="taskTime" placeholder="6:15 PM"/>',
            '<select id="taskTime"></select>',
            $html
        );

        $html = str_replace(
            <<<'JS'
      els.keyEventPreset.innerHTML = ""
      for(const p of state.keyEventPresets){
        const opt = document.createElement("option")
        opt.value = p.label
        opt.textContent = p.label
        opt.dataset.duration = p.duration
        els.keyEventPreset.appendChild(opt)
      }
      if(!els.keyEventPreset.value) els.keyEventPreset.value = "Ceremony"
JS,
            <<<'JS'
      els.keyEventPreset.innerHTML = ""
      const usedKeyEventLabels = new Set(state.blocks.filter(b => b.vendorId === KEY_VENDOR_ID).map(b => b.eventName).filter(Boolean))
      for(const p of state.keyEventPresets){
        if(usedKeyEventLabels.has(p.label)) continue
        const opt = document.createElement("option")
        opt.value = p.label
        opt.textContent = p.label
        opt.dataset.duration = p.duration
        els.keyEventPreset.appendChild(opt)
      }
      if(!els.keyEventPreset.options.length){
        const opt = document.createElement("option")
        opt.value = ""
        opt.textContent = "All key events added"
        opt.disabled = true
        els.keyEventPreset.appendChild(opt)
      } else if(els.keyEventPreset.value && ![...els.keyEventPreset.options].some(o => o.value === els.keyEventPreset.value)){
        els.keyEventPreset.selectedIndex = 0
      }
      if(!els.keyEventPreset.value && els.keyEventPreset.options.length && !els.keyEventPreset.options[0].disabled){
        els.keyEventPreset.value = els.keyEventPreset.options[0].value
      }
JS,
            $html
        );

        $html = str_replace(
            <<<'JS'
      // Vendor dropdown: existing vendors + add missing categories (one per type)
      els.vendorBlockVendor.innerHTML = ""
      for(const v of state.vendors){
        const opt = document.createElement("option")
        opt.value = "V|" + v.id
        opt.textContent = `${v.category} • ${v.name}`
        els.vendorBlockVendor.appendChild(opt)
      }
      const presentCats = new Set(state.vendors.map(v => v.category))
      if(state.vendors.length){
        const sep = document.createElement("option")
        sep.disabled = true
        sep.textContent = "────────────"
        els.vendorBlockVendor.appendChild(sep)
      }
      for(const cat of state.vendorCategoryOptions){
        if(presentCats.has(cat)) continue
        const opt = document.createElement("option")
        opt.value = "N|" + cat
        opt.textContent = cat
        els.vendorBlockVendor.appendChild(opt)
      }
      if(!els.vendorBlockVendor.value){
        if(state.vendors[0]) els.vendorBlockVendor.value = "V|" + state.vendors[0].id
        else els.vendorBlockVendor.value = "N|Photographer"
      }
JS,
            <<<'JS'
      // Vendor dropdown: existing vendors + add missing categories (one per type)
      els.vendorBlockVendor.innerHTML = ""
      const usedVendorIds = new Set(state.blocks.filter(b => b.vendorId !== KEY_VENDOR_ID).map(b => b.vendorId).filter(Boolean))
      const usedBlockCategories = new Set()
      state.blocks.forEach(b => {
        if(b.vendorId === KEY_VENDOR_ID) return
        const vv = state.vendors.find(x => x.id === b.vendorId)
        if(vv) usedBlockCategories.add(vv.category)
      })
      let hadVendorOpt = false
      for(const v of state.vendors){
        if(usedVendorIds.has(v.id)) continue
        const opt = document.createElement("option")
        opt.value = "V|" + v.id
        opt.textContent = `${v.category} • ${v.name}`
        els.vendorBlockVendor.appendChild(opt)
        hadVendorOpt = true
      }
      const presentCats = new Set(state.vendors.map(v => v.category))
      if(hadVendorOpt && state.vendors.length){
        const sep = document.createElement("option")
        sep.disabled = true
        sep.textContent = "────────────"
        els.vendorBlockVendor.appendChild(sep)
      }
      for(const cat of state.vendorCategoryOptions){
        if(presentCats.has(cat)) continue
        if(usedBlockCategories.has(cat)) continue
        const opt = document.createElement("option")
        opt.value = "N|" + cat
        opt.textContent = cat
        els.vendorBlockVendor.appendChild(opt)
      }
      if(!els.vendorBlockVendor.options.length){
        const opt = document.createElement("option")
        opt.value = ""
        opt.textContent = "All vendors are on the timeline"
        opt.disabled = true
        els.vendorBlockVendor.appendChild(opt)
      } else if(els.vendorBlockVendor.value && ![...els.vendorBlockVendor.options].some(o => o.value === els.vendorBlockVendor.value && !o.disabled)){
        els.vendorBlockVendor.selectedIndex = 0
      }
      if(!els.vendorBlockVendor.value && els.vendorBlockVendor.options.length && !els.vendorBlockVendor.options[0].disabled){
        els.vendorBlockVendor.value = els.vendorBlockVendor.options[0].value
      }
JS,
            $html
        );

        $html = str_replace(
            '      if(!vendorId) return showToast("Select a vendor")',
            '      if(!vendorId) return showToast("Select a vendor")
      if(state.blocks.some(b => b.vendorId !== KEY_VENDOR_ID && b.vendorId === vendorId)) return showToast("That vendor is already on the timeline")',
            $html
        );

        $html = str_replace(
            <<<'JS'
      } else {
        for(const v of state.vendors){
          const opt = document.createElement("option")
          opt.value = "V|" + v.id
          opt.textContent = v.category + " • " + v.name
          els.editBlockType.appendChild(opt)
        }
        els.editBlockType.value = "V|" + b.vendorId
      }
JS,
            <<<'JS'
      } else {
        const usedVendorIds = new Set(state.blocks.filter(x => x.vendorId !== KEY_VENDOR_ID && x.id !== b.id).map(x => x.vendorId).filter(Boolean))
        for(const v of state.vendors){
          if(usedVendorIds.has(v.id) && v.id !== b.vendorId) continue
          const opt = document.createElement("option")
          opt.value = "V|" + v.id
          opt.textContent = v.category + " • " + v.name
          els.editBlockType.appendChild(opt)
        }
        const curV = "V|" + b.vendorId
        els.editBlockType.value = [...els.editBlockType.options].some(o => o.value === curV) ? curV : (els.editBlockType.options[0]?.value || curV)
      }
JS,
            $html
        );

        $html = str_replace(
            '      } else if(sel.startsWith("V|")) {
        b.vendorId = sel.slice(2)
        delete b.icon
        delete b.eventName
      }',
            '      } else if(sel.startsWith("V|")) {
        const vid = sel.slice(2)
        const dupV = state.blocks.some(x => x.vendorId !== KEY_VENDOR_ID && x.id !== b.id && x.vendorId === vid)
        if(dupV) return showToast("That vendor is already on the timeline — pick another")
        b.vendorId = vid
        delete b.icon
        delete b.eventName
      }',
            $html
        );

        $html = str_replace(
            '    function addKeyEvent(){
      const presetLabel = els.keyEventPreset.value',
            '    function addKeyEvent(){
      const presetLabel = els.keyEventPreset.value
      if(!presetLabel) return showToast("All key event types are already on the timeline")
      if(state.blocks.some(b => b.vendorId === KEY_VENDOR_ID && b.eventName === presetLabel)) return showToast("That key event is already added")',
            $html
        );

        $html = str_replace(
            <<<'JS'
      els.editBlockType.innerHTML = ""
      if(b.vendorId === KEY_VENDOR_ID) {
        for(const p of state.keyEventPresets){
          const opt = document.createElement("option")
          opt.value = "K|" + p.label
          opt.textContent = "Key event: " + p.label
          els.editBlockType.appendChild(opt)
        }
        els.editBlockType.value = "K|" + (b.eventName || state.keyEventPresets[0].label)
JS,
            <<<'JS'
      els.editBlockType.innerHTML = ""
      if(b.vendorId === KEY_VENDOR_ID) {
        for(const p of state.keyEventPresets){
          const takenElsewhere = state.blocks.some(x => x.vendorId === KEY_VENDOR_ID && x.id !== b.id && x.eventName === p.label)
          if(takenElsewhere && p.label !== (b.eventName || "")) continue
          const opt = document.createElement("option")
          opt.value = "K|" + p.label
          opt.textContent = "Key event: " + p.label
          els.editBlockType.appendChild(opt)
        }
        const cur = "K|" + (b.eventName || state.keyEventPresets[0].label)
        els.editBlockType.value = [...els.editBlockType.options].some(o => o.value === cur) ? cur : (els.editBlockType.options[0]?.value || cur)
JS,
            $html
        );

        $html = str_replace(
            '      if(sel.startsWith("K|")) {
        const label = sel.slice(2)
        const p = state.keyEventPresets.find(x => x.label === label)
        b.vendorId = KEY_VENDOR_ID
        b.eventName = label
        b.icon = p?.icon || "⭐"
      } else if(sel.startsWith("V|")) {',
            '      if(sel.startsWith("K|")) {
        const label = sel.slice(2)
        const dup = state.blocks.some(x => x.vendorId === KEY_VENDOR_ID && x.id !== b.id && x.eventName === label)
        if(dup) return showToast("That key event is already used — pick another")
        const p = state.keyEventPresets.find(x => x.label === label)
        b.vendorId = KEY_VENDOR_ID
        b.eventName = label
        b.icon = p?.icon || "⭐"
      } else if(sel.startsWith("V|")) {',
            $html
        );

        $html = str_replace(
            '<div class="layout">
<div class="card">
<div class="cardHead">
<div class="cardTitleWrap">
<p class="cardTitle">Setup</p>',
            '<div class="layout">
<div class="card setupCard">
<div class="cardHead">
<div class="cardTitleWrap">
<p class="cardTitle">Setup</p>',
            $html
        );

        $html = str_replace(
            "    loadState()\n    renderAll()",
            $runtimePatch . "\n    loadState()\n    renderAll()",
            $html
        );

        $html = self::applyDownloadPdfPatch($html);

        return WinPlanningToolEnglishUi::finalizeTimeline($html);
    }

    /**
     * The couple/vendor name in the header and the "My Profile" link + photo
     * placeholder next to it were static placeholders from the design
     * export — the real name/photo already live in the app's own sidebar,
     * so we inject the actual account name here and drop the redundant
     * link + photo widget entirely.
     */
    private static function applyHeaderPatch(string $html, ?string $displayName): string
    {
        $safeName = ($displayName !== null && $displayName !== '') ? e($displayName) : 'Josh and Theresa';

        $html = str_replace(
            '<a class="coupleNameLink" href="/couples/josh-and-theresa" rel="noreferrer" target="_blank">Josh and Theresa</a>',
            '<span class="coupleNameLink">' . $safeName . '</span>',
            $html
        );

        $html = str_replace(
            '<div class="headerRight">
        <!-- TODO (developers): replace href with dynamic couple profile URL -->
        <a class="btn" href="/couples/josh-and-theresa" id="profileLink" rel="noreferrer" target="_blank">← My Profile</a>
<div aria-label="Couple profile photo placeholder" class="couplePhoto"><span>Photo</span></div>
</div>',
            '',
            $html
        );

        if ($displayName !== null && $displayName !== '') {
            $html = str_replace(
                '        name: "Josh and Theresa",',
                '        name: ' . json_encode($displayName) . ',',
                $html
            );
        }

        return $html;
    }

    /**
     * The tool's own JS ships a hardcoded demo timeline (fictional couple
     * "Josh and Theresa", 5 demo vendors, 5 vendor blocks + 3 key events)
     * as the literal default `state` object — this is not conditional on
     * whether a saved draft exists, so a brand new user with no draft would
     * otherwise see this demo wedding instead of a blank timeline. Real
     * saved data is restored separately via the localStorage bridge script,
     * so it's safe to always start from empty vendors/blocks here.
     */
    private static function applyBlankDefaultStatePatch(string $html): string
    {
        $html = str_replace(
            <<<'JS'
      vendors: [
        { id: "v1", name: "Cedar and Pine Photography", category: "Photographer" },
        { id: "v2", name: "Coastal Catering Co.", category: "Catering" },
        { id: "v3", name: "Sound Society", category: "DJ" },
        { id: "v4", name: "Evergreen Florals", category: "Florist" },
        { id: "v5", name: "Harbor Hair Studio", category: "Hair" }
      ],

      blocks: [
        { id: "b1", vendorId: "v5", startMin: 10*60, endMin: 12*60, notes: "", tasks: [
          { id:"t1", title:"Bride start", atMin: 10*60+15 },
          { id:"t2", title:"Final touch ups", atMin: 11*60+45 }
        ]},
        { id: "b2", vendorId: "v1", startMin: 11*60, endMin: 15*60, notes: "", tasks: [
          { id:"t3", title:"Details", atMin: 11*60+20 },
          { id:"t4", title:"Family formals", atMin: 14*60+40 }
        ]},
        { id: "b3", vendorId: "v4", startMin: 12*60, endMin: 14*60, notes: "", tasks: [
          { id:"t5", title:"Install ceremony florals", atMin: 12*60+20 },
          { id:"t6", title:"Reception centerpieces", atMin: 13*60+10 }
        ]},
        { id: "b4", vendorId: "v2", startMin: 14*60, endMin: 18*60, notes: "", tasks: [
          { id:"t7", title:"Serve cocktail hour", atMin: 16*60 },
          { id:"t8", title:"Dinner service start", atMin: 17*60+15 }
        ]},
        { id: "b5", vendorId: "v3", startMin: 15*60, endMin: 22*60, notes: "", tasks: [
          { id:"t9", title:"Sound check", atMin: 15*60+15 },
          { id:"t10", title:"Dance floor open", atMin: 19*60+30 }
        ]},
        { id: "k1", vendorId: KEY_VENDOR_ID, icon:"💍", eventName:"Ceremony", startMin: 16*60+30, endMin: 17*60, notes:"", tasks:[] },
        { id: "k2", vendorId: KEY_VENDOR_ID, icon:"🎤", eventName:"Speeches", startMin: 18*60+15, endMin: 18*60+35, notes:"", tasks:[] },
        { id: "k3", vendorId: KEY_VENDOR_ID, icon:"🍰", eventName:"Cake cutting", startMin: 19*60+45, endMin: 20*60, notes:"", tasks:[] }
      ],
JS,
            <<<'JS'
      vendors: [],

      blocks: [],
JS,
            $html
        );

        return $html;
    }

    private static function applyDownloadPdfPatch(string $html): string
    {
        $html = str_replace(
            '<button class="btn primary" id="exportBtn" type="button">Export</button>',
            '<button class="btn primary" id="exportBtn" type="button">Download Timeline PDF</button>',
            $html
        );

        $html = str_replace(
            '      const maxLines = 26
      const safeLines = lines.slice(0, maxLines)',
            '      const safeLines = lines',
            $html
        );

        $html = str_replace(
            <<<'JS'
      w.document.write(`<!doctype html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>${esc(coupleName)} Timeline</title>${style}</head>
      <body>
        <div class="actions">
          <button onclick="window.close()">Close</button>
          <button class="primary" onclick="window.print()">Print or Save PDF</button>
        </div>
JS,
            <<<'JS'
      const fileTitle = `${coupleName} Timeline - ${stamp}`
      w.document.write(`<!doctype html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>${esc(fileTitle)}</title>${style}</head>
      <body>
        <div class="actions">
          <button onclick="window.close()">Close</button>
          <button class="primary" onclick="window.print()">Save as PDF</button>
        </div>
JS,
            $html
        );

        $html = str_replace(
            "      w.document.close()\n    }\n\n    function exportMenu(){",
            "      w.document.close()\n      try { w.document.title = fileTitle } catch(e){}\n      setTimeout(function(){ try { w.focus(); w.print(); } catch(e){} }, 350)\n    }\n\n    function exportMenu(){",
            $html
        );

        $html = str_replace(
            <<<'CSS'
          @page{ size: 11in 8.5in; margin: 0; }
          @media print{
            body{ background:#fff; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
            .actions{ display:none !important; }
            .sheet{ width:auto; height:auto; padding:var(--margin); }
          }
CSS,
            <<<'CSS'
          @page{
            size: letter landscape;
            margin: 0.35in;
          }
          @media print{
            html, body{
              background:#fff;
              -webkit-print-color-adjust:exact;
              print-color-adjust:exact;
              margin:0;
              padding:0;
            }
            .actions{ display:none !important; }
            .sheet{
              width:auto;
              height:auto;
              padding:0;
              margin:0;
              page-break-inside: avoid;
            }
            .content{ break-inside: auto; }
            .r{ break-inside: avoid; }
          }
CSS,
            $html
        );

        return $html;
    }
}
