<?php

namespace App\Support;

class WinInvestmentPlannerHtmlPatcher
{
    public static function apply(string $html): string
    {
        $html = str_replace(["\r\n", "\r"], "\n", $html);

        $replacements = [
            'const amt = Number(b.amount||0);' => 'const amt = parseMoney(b.amount);',
            'const spentTotal=state.booked.reduce((s,b)=>s+Number(b.amount||0),0);' => 'const spentTotal=state.booked.reduce((s,b)=>s+parseMoney(b.amount),0);',
            'const spent=state.booked.reduce((s,b)=>s+Number(b.amount||0),0);' => 'const spent=state.booked.reduce((s,b)=>s+parseMoney(b.amount),0);',
            '    if(state.booked.length===0){
      // Default first row to first VENDOR_TYPES entry
      state.booked.push({vendor:"venue", cat:"venue", amount:""});
    }' => '',
            '    if(state.booked.length===0){
      // Default first row to first VENDOR_TYPES entry
      state.booked.push({vendor:"", cat:"", amount:""});
    }' => '',
            '    const opts = VENDOR_TYPES
      .filter(vt => !usedByOthers.has(vt.key) || vt.key === (b.vendor || ""))
      .map(vt => `<option value="${vt.key}" ${vt.key===(b.vendor||"")?"selected":""}>${escHtml(vt.label)}</option>`)
      .join("");' => '    const opts = `<option value="" ${!b.vendor?"selected":""}>Select vendor type</option>` + VENDOR_TYPES
      .filter(vt => !usedByOthers.has(vt.key) || vt.key === (b.vendor || ""))
      .map(vt => `<option value="${vt.key}" ${vt.key===(b.vendor||"")?"selected":""}>${escHtml(vt.label)}</option>`)
      .join("");',
            'function toggleLock(key,isBooked){
    state.locked=state.locked||{}; state.locked[key]=!state.locked[key];
    const o = buildAlloc();
    rebalanceTo100(o); state.allocPct=o;
    renderResults(true);
  }' => 'function toggleLock(key,isBooked){
    state.locked=state.locked||{};
    if(isBooked){
      state.locked[key] = true;
      toast("Booked items stay locked");
      renderResults(true);
      return;
    }
    state.locked[key]=!state.locked[key];
    state.allocPct=buildAlloc();
    renderResults(true);
  }',
            'function getSpentByVendor(){
    const m = {};
    state.booked.forEach(b=>{
      if(!b.vendor) return;
      const amt = Number(b.amount||0);
      if(amt > 0) m[b.vendor] = (m[b.vendor]||0) + amt;
    });
    return m;
  }' => 'function getSpentByVendor(){
    const m = {};
    state.booked.forEach(b=>{
      if(!b.vendor) return;
      const amt = parseMoney(b.amount);
      if(amt > 0) m[b.vendor] = (m[b.vendor]||0) + amt;
    });
    return m;
  }',
            'function freshBaseAlloc(active){
    const o = {};
    VENDOR_TYPES.forEach(vt=>{ o[vt.key] = 0; });
    if(!active || active.size === 0) return o;

    // Collect basePct weights for active vendor keys only
    const entries = [];
    let wSum = 0;
    VENDOR_TYPES.forEach(vt=>{
      if(active.has(vt.key)){
        entries.push({ key:vt.key, w:vt.basePct||5 });
        wSum += (vt.basePct||5);
      }
    });

    if(wSum === 0){
      const eq = 100 / active.size;
      active.forEach(k=>{ o[k] = eq; });
    } else {
      entries.forEach(e=>{ o[e.key] = (e.w / wSum) * 100; });
    }

    _exactSum(o, active);
    return o;
  }' => 'function freshBaseAlloc(active){
    const o = {};
    VENDOR_TYPES.forEach(vt=>{ o[vt.key] = 0; });
    if(!active || active.size === 0) return o;
    active.forEach(k=>{
      const vt = VENDOR_TYPES.find(v=>v.key===k);
      o[k] = vt ? (vt.basePct||0) : 0;
    });
    return o;
  }',
            'function rebalanceTo100(o){
    const active = activeVendorKeys();
    const pinned = new Set(
      Object.keys(state.locked||{}).filter(k => state.locked[k] && active.has(k))
    );

    _rebalance(o, active, pinned);
  }' => 'function rebalanceTo100(o){
    const active = activeVendorKeys();
    VENDOR_TYPES.forEach(vt=>{ if(!active.has(vt.key)) o[vt.key] = 0; });
  }',
            '    // If user has manually adjusted (via drag/edit), blend their adjustments in
    // but only for keys that are still active AND don\'t have a booked amount
    const spent = getSpentByVendor();
    const manualKeys = new Set();

    if(state.allocPct && state._allocManual){
      // state._allocManual tracks which keys the user manually adjusted
      state._allocManual.forEach(k=>{
        if(active.has(k) && !spent[k]) manualKeys.add(k);
      });
      manualKeys.forEach(k=>{
        if(state.allocPct[k] != null) o[k] = state.allocPct[k];
      });
    }

    // Pin booked categories at exact spent/total pct
    const pinned = new Set();
    Object.keys(spent).forEach(k=>{
      if(!active.has(k)) return;
      o[k] = Math.min(99.9, Math.max(0.001, (spent[k]/total)*100));
      pinned.add(k);
    });

    // Also pin any user-locked (non-booked) keys
    Object.keys(state.locked||{}).forEach(k=>{
      if(state.locked[k] && active.has(k) && !pinned.has(k)){
        pinned.add(k);
      }
    });

    _rebalance(o, active, pinned);
    return o;
  }

  // ── Budget allocation engine ───────────────────────────────────────────────' => '    const spent = getSpentByVendor();

    applyManualAllocOverrides(o, active, spent);

    VENDOR_TYPES.forEach(vt=>{ if(!active.has(vt.key)) o[vt.key] = 0; });
    return o;
  }

  // ── Budget allocation engine ───────────────────────────────────────────────',
            '    if(added > 0){
      const donors = [...active].filter(k=>
        k!=="other" && !pinned.has(k) && !imp.has(k) && !spl.has(k)
      );
      const dSum = donors.reduce((s,k)=>s+(o[k]||0), 0) || added;
      donors.forEach(k=>{
        o[k] = Math.max(0.5, (o[k]||0) - added*((o[k]||0)/dSum));
      });
    }

    // Auto-lock booked vendor keys (amount comes from real contract, not estimation)
    state.locked = state.locked || {};
    Object.keys(spent).forEach(k=>{ if(active.has(k)) state.locked[k] = true; });

    _rebalance(o, active, pinned);
    state.allocPct = o;
    state._allocManual = null; // clear manual adjustments after skew
  }' => '    // Auto-lock booked vendor keys (amount comes from real contract, not estimation)
    state.locked = state.locked || {};
    Object.keys(spent).forEach(k=>{ if(active.has(k)) state.locked[k] = true; });

    applyManualAllocOverrides(o, active, spent);
    state.allocPct = o;
  }',
            'if(!state.allocPct) state.allocPct=freshBaseAlloc();' => 'syncBookedVendorsForChart();
    state.allocPct=buildAlloc();',
            'const activeRows=allRows.filter(r=>r.pct>0).sort((a,b)=>b.pct-a.pct);' => 'const activeRows=allRows.filter(r=>r.isActive && (r.pct>0 || r.spent>0)).sort((a,b)=>b.pct-a.pct);',
            'const activeRows=rows.filter(r=>r.isActive&&r.pct>0);' => 'const activeRows=rows.filter(r=>r.isActive&&(r.pct>0||r.spent>0));',
            '        state.booked[idx].cat = vt ? vt.budgetKey : "other";
        render(); // re-render to refresh other dropdowns\' exclusion lists
      }
      applyPrioritySkew(); renderResults();' => '        state.booked[idx].cat = vt ? vt.budgetKey : "other";
        if(state.booked[idx].vendor && !state.planned.includes(state.booked[idx].vendor)){
          state.planned.push(state.booked[idx].vendor);
        }
        render(); // re-render to refresh other dropdowns\' exclusion lists
      }
      refreshBudgetPanelAfterBookedChange();',
            '      if(k==="amount") state.booked[idx].amount=parseMoney(el.value);
      applyPrioritySkew(); renderResults();' => '      if(k==="amount") state.booked[idx].amount=parseMoney(el.value);
      refreshBudgetPanelAfterBookedChange();',
            '      if(Number.isFinite(idx)){ state.booked.splice(idx,1); applyPrioritySkew(); render(); }' => '      if(Number.isFinite(idx)){ state.booked.splice(idx,1); render(); refreshBudgetPanelAfterBookedChange(); }',
            '    $("#addBookedBtn")?.addEventListener("click",()=>{
      const usedVendors = new Set(state.booked.map(b=>b.vendor||""));
      const firstUnused = VENDOR_TYPES.find(vt=>!usedVendors.has(vt.key));
      if(firstUnused){
        state.booked.push({vendor:firstUnused.key, cat:firstUnused.budgetKey, amount:""});
        render();
      }
    });' => '    $("#addBookedBtn")?.addEventListener("click",()=>{
      state.booked.push({vendor:"", cat:"", amount:""});
      render();
      refreshBudgetPanelAfterBookedChange();
    });',
            '        const newPct=clamp((parseMoney(inp.value)/t)*100, 0.5, 99);
        const o = buildAlloc();
        o[key]=newPct;
        rebalanceTo100(o);
        state.allocPct=o;
        state._allocManual = state._allocManual||new Set(); state._allocManual.add(key);
        renderResults(true); saveDraft();' => '        const newPct=clamp((parseMoney(inp.value)/t)*100, 0, 100);
        const o = buildAlloc();
        o[key]=newPct;
        state.allocPct=o;
        state._allocManual = state._allocManual||new Set();
        state._allocManual.add(key);
        rebalanceTo100(o);
        renderResults(true); saveDraft();',
            '      ${winPanel}
      <div class="smallPrint">Lock a category to keep it fixed while others rebalance.</div>`;' => '      ${winPanel}
      ${buildSurplusPanel(total, spentTotal, activeRows)}
      <div class="smallPrint">Lock a category to keep it fixed while others rebalance.</div>`;',
            '      state.allocPct = freshBaseAlloc();
      applyPrioritySkew();
      render();' => '      applyPrioritySkew();
      render();',
            '  nextBtn.addEventListener("click",()=>{
    if(state.step===0&&parseMoney(state.totalBudget)<=0){ toast("Please enter a budget"); return; }

    // Moving from step 2 → 3: normalise booked rows, seed planned, reset allocation' => '  nextBtn.addEventListener("click",()=>{
    if(state.step===0&&parseMoney(state.totalBudget)<=0){ toast("Please enter a budget"); return; }
    if(state.step!==3){
      flushPlannedAmountDrafts();
    }

    // Moving from step 2 → 3: normalise booked rows, seed planned, reset allocation',
            '    // Moving from step 3 → 4: reset allocation for new selection set
    if(state.step===2){
      state.allocPct = null;
      state._allocManual = null;
      state.locked = {};
    }

    if(state.step===3){' => '    if(state.step===3){',
            '3. Your Team' => '3. Extra Services',
            'font-weight:700;font-size:12px;color:var(--muted);' => 'font-weight:700;font-size:14px;color:var(--muted);',
            '  .stepPill{font-size:11px;padding:6px 10px;}' => '  .stepPill{font-size:13px;padding:6px 10px;}',
            '  .stepPill{font-size:10px;padding:5px 9px;}' => '  .stepPill{font-size:12px;padding:5px 9px;}',
            '  padding:10px 10px 0;display:flex;flex-direction:column;align-items:center;background:#fff;overflow:hidden;' => '  padding:10px 10px 0;display:flex;flex-direction:column;align-items:center;background:#fff;overflow:visible;',
            '<motion style="text-align:center;padding:6px 12px 2px;font-size:11px;color:rgba(21,21,21,.40);font-style:italic;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">📊 ${escHtml(NE_DISCLAIMER)}</div>' => '<div class="donutDisclaimer">📊 ${escHtml(NE_DISCLAIMER)}</div>',
        ];

        $html = str_replace(array_keys($replacements), array_values($replacements), $html);

        $html = str_replace(
            '<div style="text-align:center;padding:6px 12px 2px;font-size:11px;color:rgba(21,21,21,.40);font-style:italic;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">📊 ${escHtml(NE_DISCLAIMER)}</div>',
            '<div class="donutDisclaimer">📊 ${escHtml(NE_DISCLAIMER)}</div>',
            $html
        );

        $basePctReplacements = [
            '{ key:"venue",        label:"Venue",             icon:\'<img src="https://weddinginsidersnetwork.com/ico/venue.svg" class="winIcon" alt="">\',          budgetKey:"venue",        primary:true,  color:"#FF7A9B", basePct:18 },' => '{ key:"venue",        label:"Venue",             icon:\'<img src="https://weddinginsidersnetwork.com/ico/venue.svg" class="winIcon" alt="">\',          budgetKey:"venue",        primary:true,  color:"#FF7A9B", basePct:10 },',
            '{ key:"planner",      label:"Wedding Planner",   icon:\'<img src="https://weddinginsidersnetwork.com/ico/plan.svg" class="winIcon" alt="">\',           budgetKey:"planning",     primary:true,  color:"#C7A6FF", basePct:7 },' => '{ key:"planner",      label:"Wedding Planner",   icon:\'<img src="https://weddinginsidersnetwork.com/ico/plan.svg" class="winIcon" alt="">\',           budgetKey:"planning",     primary:true,  color:"#C7A6FF", basePct:6 },',
            '{ key:"photographer", label:"Photographer",      icon:\'<img src="https://weddinginsidersnetwork.com/ico/camera.svg" class="winIcon" alt="">\',         budgetKey:"photoVideo",   primary:true,  color:"#FFD27A", basePct:12 },' => '{ key:"photographer", label:"Photographer",      icon:\'<img src="https://weddinginsidersnetwork.com/ico/camera.svg" class="winIcon" alt="">\',         budgetKey:"photoVideo",   primary:true,  color:"#FFD27A", basePct:10 },',
            '{ key:"hair",         label:"Hair & Makeup",     icon:\'<img src="https://weddinginsidersnetwork.com/ico/hair.svg" class="winIcon" alt="">\',           budgetKey:"attireBeauty", primary:true,  color:"#7AFFB6", basePct:3 },' => '{ key:"hair",         label:"Hair & Makeup",     icon:\'<img src="https://weddinginsidersnetwork.com/ico/hair.svg" class="winIcon" alt="">\',           budgetKey:"attireBeauty", primary:true,  color:"#7AFFB6", basePct:1 },',
            '{ key:"videographer", label:"Videographer",      icon:\'<img src="https://weddinginsidersnetwork.com/ico/video.svg" class="winIcon" alt="">\',          budgetKey:"photoVideo",   primary:false,  color:"#FFB27A", basePct:8 },' => '{ key:"videographer", label:"Videographer",      icon:\'<img src="https://weddinginsidersnetwork.com/ico/video.svg" class="winIcon" alt="">\',          budgetKey:"photoVideo",   primary:false,  color:"#FFB27A", basePct:6 },',
            '{ key:"catering",     label:"Caterer",           icon:\'<img src="https://weddinginsidersnetwork.com/ico/caterer.svg" class="winIcon" alt="">\',        budgetKey:"catering",     primary:true,  color:"#8BFFC7", basePct:22 },' => '{ key:"catering",     label:"Caterer",           icon:\'<img src="https://weddinginsidersnetwork.com/ico/caterer.svg" class="winIcon" alt="">\',        budgetKey:"catering",     primary:true,  color:"#8BFFC7", basePct:20 },',
            '{ key:"bakery",       label:"Bakery / Cake",     icon:\'<img src="https://weddinginsidersnetwork.com/ico/cake.svg" class="winIcon" alt="">\',           budgetKey:"catering",     primary:false,  color:"#FFE4B5", basePct:4 },' => '{ key:"bakery",       label:"Bakery / Cake",     icon:\'<img src="https://weddinginsidersnetwork.com/ico/cake.svg" class="winIcon" alt="">\',           budgetKey:"catering",     primary:false,  color:"#FFE4B5", basePct:3 },',
            '{ key:"decor",        label:"Rentals & Decor",   icon:\'<img src="https://weddinginsidersnetwork.com/ico/rentals.svg" class="winIcon" alt="">\',        budgetKey:"rentalsDecor", primary:true,  color:"#7AE6FF", basePct:5 },' => '{ key:"decor",        label:"Rentals & Decor",   icon:\'<img src="https://weddinginsidersnetwork.com/ico/rentals.svg" class="winIcon" alt="">\',        budgetKey:"rentalsDecor", primary:true,  color:"#7AE6FF", basePct:4 },',
            '{ key:"bar",          label:"Bar Services",      icon:\'<img src="https://weddinginsidersnetwork.com/ico/bar.svg" class="winIcon" alt="">\',            budgetKey:"bar",          primary:true,  color:"#7AA7FF", basePct:14 },' => '{ key:"bar",          label:"Bar Services",      icon:\'<img src="https://weddinginsidersnetwork.com/ico/bar.svg" class="winIcon" alt="">\',            budgetKey:"bar",          primary:true,  color:"#7AA7FF", basePct:15 },',
            '{ key:"stationery",   label:"Stationery",        icon:\'<img src="https://weddinginsidersnetwork.com/ico/stationery.svg" class="winIcon" alt="">\',     budgetKey:"stationery",   primary:true,  color:"#FFC4E1", basePct:2 },' => '{ key:"stationery",   label:"Stationery",        icon:\'<img src="https://weddinginsidersnetwork.com/ico/stationery.svg" class="winIcon" alt="">\',     budgetKey:"stationery",   primary:true,  color:"#FFC4E1", basePct:1 },',
            '{ key:"officiant",    label:"Officiant",         icon:\'<img src="https://weddinginsidersnetwork.com/ico/officiant.svg" class="winIcon" alt="">\',      budgetKey:"planning",     primary:false,  color:"#D4E6FF", basePct:2 },' => '{ key:"officiant",    label:"Officiant",         icon:\'<img src="https://weddinginsidersnetwork.com/ico/officiant.svg" class="winIcon" alt="">\',      budgetKey:"planning",     primary:false,  color:"#D4E6FF", basePct:1 },',
            '{ key:"transport",    label:"Transportation",    icon:\'<img src="https://weddinginsidersnetwork.com/ico/transportation.svg" class="winIcon" alt="">\',  budgetKey:"transport",    primary:true,  color:"#FFEACC", basePct:3 },' => '{ key:"transport",    label:"Transportation",    icon:\'<img src="https://weddinginsidersnetwork.com/ico/transportation.svg" class="winIcon" alt="">\',  budgetKey:"transport",    primary:true,  color:"#FFEACC", basePct:2 },',
            '{ key:"jewelers",     label:"Jewelers",          icon:\'<img src="https://weddinginsidersnetwork.com/ico/jewel.svg" class="winIcon" alt="">\',          budgetKey:"jeweler",      primary:true,  color:"#FFB0C0", basePct:5 },' => '{ key:"jewelers",     label:"Jewelers",          icon:\'<img src="https://weddinginsidersnetwork.com/ico/jewel.svg" class="winIcon" alt="">\',          budgetKey:"jeweler",      primary:true,  color:"#FFB0C0", basePct:4 },',
            '{ key:"florist",      label:"Florist",           icon:\'<img src="https://weddinginsidersnetwork.com/ico/florist.svg" class="winIcon" alt="">\',        budgetKey:"florals",      primary:true,  color:"#FF9B9B", basePct:7 },' => '{ key:"florist",      label:"Florist",           icon:\'<img src="https://weddinginsidersnetwork.com/ico/florist.svg" class="winIcon" alt="">\',        budgetKey:"florals",      primary:true,  color:"#FF9B9B", basePct:6 },',
            '{ key:"band",         label:"Live Bands",        icon:\'<img src="https://weddinginsidersnetwork.com/ico/music.svg" class="winIcon" alt="">\',          budgetKey:"music",        primary:false,  color:"#A8D8FF", basePct:7 },' => '{ key:"band",         label:"Live Bands",        icon:\'<img src="https://weddinginsidersnetwork.com/ico/music.svg" class="winIcon" alt="">\',          budgetKey:"music",        primary:false,  color:"#A8D8FF", basePct:6 },',
            '{ key:"photobooth",   label:"Photo Booth",       icon:\'<img src="https://weddinginsidersnetwork.com/ico/photo-booth.svg" class="winIcon" alt="">\',   budgetKey:"optional",     primary:true,  color:"#FFD4A0", basePct:3 },' => '{ key:"photobooth",   label:"Photo Booth",       icon:\'<img src="https://weddinginsidersnetwork.com/ico/photo-booth.svg" class="winIcon" alt="">\',   budgetKey:"optional",     primary:true,  color:"#FFD4A0", basePct:1 },',
            '{ key:"strings",      label:"String Ensembles",  icon:\'<img src="https://weddinginsidersnetwork.com/ico/violin.svg" class="winIcon" alt="">\',         budgetKey:"music",        primary:false,  color:"#B8FFE4", basePct:3 },' => '{ key:"strings",      label:"String Ensembles",  icon:\'<img src="https://weddinginsidersnetwork.com/ico/violin.svg" class="winIcon" alt="">\',         budgetKey:"music",        primary:false,  color:"#B8FFE4", basePct:1 },',
            '{ key:"content",      label:"Content Creators",  icon:\'<img src="https://weddinginsidersnetwork.com/ico/video.svg" class="winIcon" alt="">\',          budgetKey:"optional",     primary:false,  color:"#C0F0C0", basePct:3 },' => '{ key:"content",      label:"Content Creators",  icon:\'<img src="https://weddinginsidersnetwork.com/ico/video.svg" class="winIcon" alt="">\',          budgetKey:"optional",     primary:false,  color:"#C0F0C0", basePct:1.5 },',
            '{ key:"artist",       label:"Live Artists",      icon:\'<img src="https://weddinginsidersnetwork.com/ico/painter.svg" class="winIcon" alt="">\',        budgetKey:"optional",     primary:false,  color:"#F0D4FF", basePct:3 },' => '{ key:"artist",       label:"Live Artists",      icon:\'<img src="https://weddinginsidersnetwork.com/ico/painter.svg" class="winIcon" alt="">\',        budgetKey:"optional",     primary:false,  color:"#F0D4FF", basePct:4 },',
        ];
        $html = str_replace(array_keys($basePctReplacements), array_values($basePctReplacements), $html);

        $html = str_replace(
            '  function saveDraft(){
    try{ localStorage.setItem(STORAGE_KEY,JSON.stringify(state)); toast("Saved ✓"); }
    catch(e){ toast("Save failed"); }
  }',
            '  function saveDraft(){
    try{
      const payload = Object.assign({}, state, {
        _allocManual: state._allocManual instanceof Set ? [...state._allocManual] : Array.isArray(state._allocManual) ? state._allocManual : []
      });
      localStorage.setItem(STORAGE_KEY,JSON.stringify(payload)); toast("Saved ✓");
    }
    catch(e){ toast("Save failed"); }
  }',
            $html
        );

        $html = str_replace(
            '      state.allocPct    = (obj.allocPct&&typeof obj.allocPct==="object") ? obj.allocPct : null;
      state.locked      = (obj.locked  &&typeof obj.locked  ==="object") ? obj.locked   : {};
    }catch(e){}
  }',
            '      state.allocPct    = (obj.allocPct&&typeof obj.allocPct==="object") ? obj.allocPct : null;
      state.locked      = (obj.locked  &&typeof obj.locked  ==="object") ? obj.locked   : {};
      state._allocManual = Array.isArray(obj._allocManual) ? new Set(obj._allocManual) : new Set();
    }catch(e){}
  }',
            $html
        );

        $helperFunctions = <<<'JS'
  const PRIORITY_BUMP_PCT = { important: 5, splurge: 10 };
  function applyPriorityToAlloc(o, active, pinned){
    const imp = new Set(state.important || []);
    const spl = new Set(state.splurge || []);
    const pinnedSet = pinned instanceof Set ? pinned : new Set(pinned || []);
    let bumpTotal = 0;

    active.forEach(k=>{
      if(k === "other" || pinnedSet.has(k)) return;
      const bump = spl.has(k) ? PRIORITY_BUMP_PCT.splurge : (imp.has(k) ? PRIORITY_BUMP_PCT.important : 0);
      if(!bump) return;
      o[k] = (o[k] || 0) + bump;
      bumpTotal += bump;
    });

    if(bumpTotal > 0){
      const trimKeys = [...active].filter(k=>
        k !== "other" && !pinnedSet.has(k) && !imp.has(k) && !spl.has(k)
      );
      const trimPool = trimKeys.reduce((s,k)=>s+(o[k]||0), 0) || bumpTotal;
      trimKeys.forEach(k=>{
        o[k] = Math.max(0, (o[k]||0) - bumpTotal * ((o[k]||0) / trimPool));
      });
    }

    return o;
  }
  function normalizeAllocManualStore(){
    if(Array.isArray(state._allocManual)){
      state._allocManual = new Set(state._allocManual.filter(Boolean));
    } else if(!(state._allocManual instanceof Set)){
      state._allocManual = new Set();
    }
  }
  function applyManualAllocOverrides(o, active, spent){
    normalizeAllocManualStore();
    if(!state.allocPct || !state._allocManual || !state._allocManual.size) return;
    state._allocManual.forEach(k=>{
      if(!active.has(k) || (spent[k]||0) > 0) return;
      if(state.allocPct[k] != null) o[k] = state.allocPct[k];
    });
  }
  function syncBookedVendorsForChart(){
    bookedVendorKeys().forEach(k=>{
      if(!state.planned.includes(k)) state.planned.push(k);
    });
  }
  function refreshBudgetPanelAfterBookedChange(){
    syncBookedVendorsForChart();
    state.allocPct = buildAlloc();
    applyPrioritySkew();
    renderResults(true);
  }
  function flushPlannedAmountDrafts(){
    const total=parseMoney(state.totalBudget);
    if(total<=0) return;
    const active=activeVendorKeys();
    if(active.size===0) return;
    let changed=false;
    const o=state.allocPct && typeof state.allocPct==="object" ? Object.assign({}, state.allocPct) : buildAlloc();
    resultsBody.querySelectorAll("input[data-money]:not([disabled])").forEach(inp=>{
      const key=inp.dataset.money;
      if(!key || !active.has(key) || (state.locked||{})[key]) return;
      const newPct=clamp((parseMoney(inp.value)/total)*100, 0, 100);
      o[key]=newPct;
      state._allocManual=state._allocManual||new Set();
      state._allocManual.add(key);
      changed=true;
    });
    if(changed) state.allocPct=o;
    normalizeAllocManualStore();
  }
  function buildSurplusPanel(total, spentTotal, activeRows){
    const recommended = activeRows.reduce((sum, row)=>sum + Math.max(row.planned, row.spent), 0);
    const surplus = total - recommended;
    if(!(surplus > 0)) return "";
    return `<div class="note" style="margin-top:14px;line-height:1.65;">
      <strong>Congratulations!</strong> Based on general regional averages, you are under budget by ${fmtMoney(Math.round(surplus))}. How would you like to distribute those funds?
      <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;">
        <button class="btnGhost" type="button" id="surplusExistingBtn">Assign more to existing services</button>
        <button class="btnGhost" type="button" id="surplusNewBtn">Assign to new services</button>
      </div>
    </div>`;
  }
JS;

        $html = str_replace(
            "  // ── Vendor helpers ────────────────────────────────────────────────────────\n",
            $helperFunctions . "\n  // ── Vendor helpers ────────────────────────────────────────────────────────\n",
            $html
        );

        $surplusHandlers = <<<'JS'
    document.getElementById("surplusExistingBtn")?.addEventListener("click",()=>{
      resultsBody.querySelector(".legendItem input[data-money]:not([disabled])")?.focus();
      resultsBody.querySelector(".legend")?.scrollIntoView({behavior:"smooth", block:"nearest"});
    });
    document.getElementById("surplusNewBtn")?.addEventListener("click",()=>{
      state.completed = false;
      state.step = 2;
      render();
      wizardBody?.scrollIntoView({behavior:"smooth", block:"start"});
    });
JS;

        $html = str_replace(
            '    if(keepScroll) resultsBody.scrollTop=scrollTop;
  }',
            $surplusHandlers . "\n    if(keepScroll) resultsBody.scrollTop=scrollTop;\n  }",
            $html
        );

        $plannerCss = <<<'HTML'
<style>
.donutDisclaimer{
  width:100%;
  box-sizing:border-box;
  text-align:center;
  padding:8px 16px 6px;
  font-size:11px;
  color:rgba(21,21,21,.40);
  font-style:italic;
  line-height:1.45;
  white-space:normal;
}
</style>
HTML;
        $html = preg_replace('/<\/head>/', $plannerCss . '</head>', $html, 1) ?? $html;

        $html = str_replace(
            '  // applyPrioritySkew: bumps allocation for priority vendors (imp=+5pts, splurge=+10pts).',
            '  // applyPrioritySkew: adds allocation share for priority vendors (important=+5%, splurge=+10%).',
            $html
        );
        $html = str_replace(
            <<<'JS'
    const imp = new Set(state.important);
    const spl = new Set(state.splurge);

    let added = 0;
    active.forEach(k=>{
      if(k==="other" || pinned.has(k)) return;
      const bump = spl.has(k)?10 : imp.has(k)?5 : 0;
      if(bump){ o[k] = (o[k]||0)+bump; added += bump; }
    });
JS,
            '    applyPriorityToAlloc(o, active, pinned);',
            $html
        );
        $html = str_replace(
            <<<'JS'
    const spent  = getSpentByVendor();
    const o      = buildAlloc();

    return VENDOR_TYPES
JS,
            <<<'JS'
    const spent  = getSpentByVendor();
    const o0     = buildAlloc();
    const pinned = new Set();
    Object.keys(spent).forEach(k=>{ if(active.has(k)) pinned.add(k); });
    Object.keys(state.locked||{}).forEach(k=>{ if(state.locked[k] && active.has(k)) pinned.add(k); });
    const o      = applyPriorityToAlloc(Object.assign({}, o0), active, pinned);

    return VENDOR_TYPES
JS,
            $html
        );
        $html = str_replace('adds 5 pts each', 'adds 5% each', $html);
        $html = str_replace('adds 10 pts each', 'adds 10% each', $html);

        $html = str_replace(
            '        <div class="chips" id="impChips">${freeList.map(vt=>chip(vt, impSet.has(vt.key))).join("")}</div>',
            '        <div class="chips" id="impChips">${freeList.filter(vt=>!splSet.has(vt.key)).map(vt=>chip(vt, impSet.has(vt.key))).join("")}</div>',
            $html
        );
        $html = str_replace(
            '        <div class="hint" style="margin-top:6px;">Selecting "most important" removes it from splurge options.</div>',
            '        <div class="hint" style="margin-top:6px;">A vendor can only be in one list — choosing here removes it from the other.</div>',
            $html
        );
        $html = str_replace(
            '      else{ if(state.splurge.length>=2){ toast("Max 2 selections"); return; } state.splurge.push(vk); }',
            '      else{ if(state.splurge.length>=2){ toast("Max 2 selections"); return; } state.splurge.push(vk); state.important=state.important.filter(x=>x!==vk); }',
            $html
        );

        return WinPlanningToolEnglishUi::appendInvestmentPlannerOverrides($html);
    }
}
