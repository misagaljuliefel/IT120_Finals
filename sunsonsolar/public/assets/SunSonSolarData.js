function getAccounts() {
    return JSON.parse(localStorage.getItem("ssAccounts") || "[]");
}

function saveAccounts(accounts) {
    localStorage.setItem("ssAccounts", JSON.stringify(accounts));
}

function findAccount(uname) {
    var accounts = getAccounts();
    for (var i = 0; i < accounts.length; i++) {
        if (accounts[i].uname.toLowerCase() === uname.toLowerCase()) {
            return accounts[i];
        }
    }
    return null;
}

function registerAccount(account) {
    if (findAccount(account.uname)) {
        alert("That username is already taken. Please choose another.");
        return false;
    }

    var accounts = getAccounts();
    for (var i = 0; i < accounts.length; i++) {
        if ((accounts[i].email || "").toLowerCase() === (account.email || "").toLowerCase()) {
            alert("That email address is already registered.");
            return false;
        }
    }

    // Employees get a role from their department; everyone else is a customer.
    account.role = account.department ? roleForDepartment(account.department) : "customer";
    account.active = true;
    accounts.push(account);
    saveAccounts(accounts);
    return true;
}

function updateAccount(oldUname, updates) {
    var accounts = getAccounts();
    for (var i = 0; i < accounts.length; i++) {
        if (accounts[i].uname.toLowerCase() === oldUname.toLowerCase()) {
            for (var key in updates) {
                accounts[i][key] = updates[key];
            }
            saveAccounts(accounts);
            sessionStorage.setItem("ssCurrentUser", JSON.stringify(accounts[i]));
            return true;
        }
    }
    return false;
}

function loginAccount(uname, password) {
    var account = findAccount(uname);
    if (!account || account.password !== password) {
        return false;
    }
    if (account.active === false) {
        return "disabled";
    }
    sessionStorage.setItem("ssCurrentUser", JSON.stringify(account));
    return true;
}

function getCurrentUser() {
    var raw = sessionStorage.getItem("ssCurrentUser");
    return raw ? JSON.parse(raw) : null;
}

function logoutAccount() {
    sessionStorage.removeItem("ssCurrentUser");
    sessionStorage.removeItem("ssQuoteList");
}

function requireLogin() {
    var user = getCurrentUser();
    if (!user) {
        var here = window.location.pathname.split("/").pop();
        window.location.replace("login" + (here ? "?next=" + encodeURIComponent(here + window.location.search) : ""));
    }
    return user;
}

function requireRole(allowedRoles) {
    var user = requireLogin();
    if (user && allowedRoles.indexOf(roleOf(user)) === -1) {
        sessionStorage.setItem("ssFlash", "That page isn't available for your account.");
        window.location.replace(homeFor(user));
        return null;
    }
    return user;
}

function addTimeLog(entry) {
    var logs = JSON.parse(localStorage.getItem("ssTimeLogs") || "[]");
    logs.unshift(entry);
    localStorage.setItem("ssTimeLogs", JSON.stringify(logs));
}

function getTimeLogs() {
    return JSON.parse(localStorage.getItem("ssTimeLogs") || "[]");
}

function getQuoteList() {
    return JSON.parse(sessionStorage.getItem("ssQuoteList") || "[]");
}

function addToQuoteList(item) {
    var list = getQuoteList();
    for (var i = 0; i < list.length; i++) {
        if (list[i].id === item.id) {
            return list;
        }
    }
    list.push(item);
    sessionStorage.setItem("ssQuoteList", JSON.stringify(list));
    return list;
}

function removeFromQuoteList(id) {
    var list = getQuoteList().filter(function (item) {
        return item.id !== id;
    });
    sessionStorage.setItem("ssQuoteList", JSON.stringify(list));
    return list;
}

function clearQuoteList() {
    sessionStorage.removeItem("ssQuoteList");
}

var CATALOG = [

    { id: "panel-1", type: "product", cat: "Panels", name: "Risen 400W Monocrystalline Solar Panels | Tier 1, Durable", price: 11500, desc: "High-efficiency monocrystalline solar panel built for maximum yield.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQhtxeq60o74FyzKtuJLV4cCoUYZCU1z3AOLDxj0P_LAw&s=10" },
    { id: "panel-2", type: "product", cat: "Panels", name: "EcoFlow 400W Lightweight Portable Solar Panel | EcoFlow PH", price: 16200, desc: "EcoFlow 400W Lightweight Portable Solar Panel | EcoFlow PH", img: "https://ph.ecoflow.com/cdn/shop/files/ecoflow-400w-portable-solar-panel-1186050766.png?v=1774335746" },
    { id: "panel-3", type: "product", cat: "Panels", name: "400 Watt Solar Panel Price in Pakistan 2026", price: 6800, desc: "Lightweight and bendable panel ideal for curved roofs and vehicles.", img: "https://paksolarpanelprices.com/wp-content/uploads/2026/07/400-Watt-Solar-Panel.webp" },
    { id: "panel-4", type: "product", cat: "Panels", name: "Poly 320W Standard Panel", price: 8500, desc: "Cost-effective polycrystalline panel for traditional grid setups.", img: "https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?auto=format&fit=crop&w=600&q=80" },
    { id: "panel-5", type: "product", cat: "Panels", name: "Commercial 600W Ultra Panel", price: 19500, desc: "Heavy-duty high-wattage panel engineered for industrial solar farms.", img: "https://images.unsplash.com/photo-1558449028-b53a39d100fc?auto=format&fit=crop&w=600&q=80" },

    { id: "inv-1", type: "product", cat: "Inverters", name: "Qoltec Modular Hybrid Solar Inverter Off-Grid 5kW", price: 45000, desc: "Advanced power inverter for reliable off-grid and hybrid energy conversion.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTa1hyG7ySDVuyalmUAUoOAQFoaTVIlQM9b1oS9x9Dqvs99MpOvG4G0D-0&s=10" },
    { id: "inv-2", type: "product", cat: "Inverters", name: "ECO Series (2-12kW) – Solar Power Inverters/UPS/ESS System Factory", price: 28000, desc: "Transformerless inverter optimized for residential net-metering systems.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSxI6cMiBt0zQS65M8M-mJmHnR-iZMPqh_cEKAT9JLatXw4OLoYdbDObWc&s=10" },
    { id: "inv-3", type: "product", cat: "Inverters", name: "Everything You Need To Know About Solar Inverters | WHC SOLAR", price: 85000, desc: "High-capacity commercial inverter with dual MPPT tracking.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcThw84b0HUKRQ2x3W4YXzUuHF2j-Fu2zwk-t7f1AiaIWG0rd98iLrAXOjU&s=10" },
    { id: "inv-4", type: "product", cat: "Inverters", name: "Hybrid Solar Inverter 1.0-10.2KW 12V 24V 48V Pure Philippines", price: 7500, desc: "Panel-level optimization inverter for maximum shade resilience.", img: "https://m.media-amazon.com/images/I/71qvHMcZQwL._AC_SX569_.jpg" },
    { id: "inv-5", type: "product", cat: "Inverters", name: "Off-Grid Pure Sine Wave Inverter 3kW", price: 32000, desc: "Standalone inverter designed for remote cabins and backup power.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXKWDqHuVIQsYwIlbY4M3UMmmU89wgx83TU1za8teMbPfEzYKNSGZrJMVt&s=10" },

    { id: "bat-1", type: "product", cat: "Batteries", name: "Lithium-ion 48V 100Ah Battery", price: 65000, desc: "Long-lasting energy storage unit with smart BMS for 24/7 power.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRFUW51IPPsb5BajgN_PqjyjSIPIBIAXjOkP7GbCVNN7hWvQMwvx7mqvkA&s=10" },
    { id: "bat-2", type: "product", cat: "Batteries", name: "LiFePO4 24V 200Ah Powerwall", price: 82000, desc: "Deep-cycle wall-mounted lithium iron phosphate battery pack.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTy7Rx5L1d2NSvh8wkeuSkj9bdF7ws52mztRGE036F8gjjRjng9-LAjq2o&s=10" },
    { id: "bat-3", type: "product", cat: "Batteries", name: "Tubular Deep Cycle Battery 12V 250Ah", price: 18500, desc: "Heavy-duty lead-acid battery for standard off-grid solar kits.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQdYL6y9LEfMkxz9OOFDE45EujlFHAIpqnTuMZUUqOAdxs1YwFf7Z4lfK8&s=10" },
    { id: "bat-4", type: "product", cat: "Batteries", name: "Modular Stackable Battery 5kWh", price: 74000, desc: "Expandable battery module for growing residential energy storage needs.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQLn_r2MZP-hRwsMNcjU3NthDcazVh609KbFBEmqv3krK_hV5xqg-pe14w&s=10" },
    { id: "bat-5", type: "product", cat: "Batteries", name: "Portable Solar Generator Station 1000Wh", price: 39900, desc: "All-in-one portable power bank with AC outlets and solar charging.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRmSpL43Pd__WQ6CRvXgXOtoZ8LBTZD5wRJnYcDo3-oqvpYXY5_V0hr5h4&s=10" },

    { id: "rack-1", type: "product", cat: "Racking & Mounting", name: "Tile Roof Mounting Kit (4 Panels)", price: 4500, desc: "Heavy-duty structural mounting system for residential tile roofs.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQVpGthQcGJAo1d6XJQHAZSuR2Ean3dcWOwncqMwsKjOg&s=10" },
    { id: "rack-2", type: "product", cat: "Racking & Mounting", name: "Metal Tin Roof L-Feet Bracket Set", price: 3200, desc: "Corrosion-resistant aluminum L-feet brackets for corrugated roofs.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQVpGthQcGJAo1d6XJQHAZSuR2Ean3dcWOwncqMwsKjOg&s=10" },
    { id: "rack-3", type: "product", cat: "Racking & Mounting", name: "Flat Roof Ballasted Triangle Mount", price: 6800, desc: "Non-penetrating ballasted mounting structure for flat concrete roofs.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQVpGthQcGJAo1d6XJQHAZSuR2Ean3dcWOwncqMwsKjOg&s=10" },
    { id: "rack-4", type: "product", cat: "Racking & Mounting", name: "Ground Mount Rack System (6 Panels)", price: 12500, desc: "Galvanized steel ground mounting rack for open land installations.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQVpGthQcGJAo1d6XJQHAZSuR2Ean3dcWOwncqMwsKjOg&s=10" },
    { id: "rack-5", type: "product", cat: "Racking & Mounting", name: "Adjustable Tilt Leg Kit (10-30 deg)", price: 2500, desc: "Angle-adjustable brackets to maximize solar absorption angle.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQVpGthQcGJAo1d6XJQHAZSuR2Ean3dcWOwncqMwsKjOg&s=10" },

    { id: "wire-1", type: "product", cat: "Wires", name: "Solar PV Cable 10 AWG (100m Roll)", price: 4200, desc: "Weatherproof, high-conductivity tinned copper wire for PV systems.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRgZ4RtFM0M3wh_wKkD6WfrDwf-s-zVUUmZbZsckLLfW2R8zm9BQi6cUaMN&s=10" },
    { id: "wire-2", type: "product", cat: "Wires", name: "MC4 Solar Panel Connector Pairs (10pcs)", price: 650, desc: "Waterproof IP67 quick-connect plugs for solar cable termination.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRgZ4RtFM0M3wh_wKkD6WfrDwf-s-zVUUmZbZsckLLfW2R8zm9BQi6cUaMN&s=10" },
    { id: "wire-3", type: "product", cat: "Wires", name: "Heavy-Duty Battery Interconnect Cable 2AWG", price: 1200, desc: "Thick flexible copper cable for linking inverter to battery banks.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRgZ4RtFM0M3wh_wKkD6WfrDwf-s-zVUUmZbZsckLLfW2R8zm9BQi6cUaMN&s=10" },
    { id: "wire-4", type: "product", cat: "Wires", name: "DC Circuit Breaker 63A 500V", price: 950, desc: "Overload and short-circuit protection breaker for DC solar lines.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRgZ4RtFM0M3wh_wKkD6WfrDwf-s-zVUUmZbZsckLLfW2R8zm9BQi6cUaMN&s=10" },
    { id: "wire-5", type: "product", cat: "Wires", name: "Lightning Surge Protector Device (SPD DC)", price: 1400, desc: "Protects your inverter and electronics from lightning surges.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRgZ4RtFM0M3wh_wKkD6WfrDwf-s-zVUUmZbZsckLLfW2R8zm9BQi6cUaMN&s=10" },

    { id: "serv-1", type: "service", cat: "Consultation", name: "Comprehensive Site Evaluation", price: 1500, desc: "Professional site evaluation, shade testing, and custom energy cost analysis.", img: "https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=600&q=80" },
    { id: "serv-2", type: "service", cat: "Consultation", name: "Commercial Solar Feasibility Audit", price: 4500, desc: "In-depth energy audit and load profile analysis for businesses.", img: "https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=600&q=80" },
    { id: "serv-3", type: "service", cat: "Consultation", name: "Off-Grid Power System Assessment", price: 2000, desc: "Remote location power requirement evaluation and battery sizing.", img: "https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=600&q=80" },
    { id: "serv-4", type: "service", cat: "Consultation", name: "Net Metering Eligibility Check", price: 1000, desc: "Meralco utility compliance check for grid feedback credits.", img: "https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=600&q=80" },
    { id: "serv-5", type: "service", cat: "Consultation", name: "Virtual Solar Energy Consultation", price: 500, desc: "Online video consultation with a certified solar engineer.", img: "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=600&q=80" },

    { id: "des-1", type: "service", cat: "Designing", name: "Custom CAD Solar Layout & Engineering", price: 3500, desc: "Custom engineering plans optimized specifically for your roof shape.", img: "https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=600&q=80" },
    { id: "des-2", type: "service", cat: "Designing", name: "Commercial Rooftop 3D Modeling", price: 8000, desc: "Advanced 3D simulation showing shading analysis and panel tilt.", img: "https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80" },
    { id: "des-3", type: "service", cat: "Designing", name: "Electrical Single Line Diagram (SLD)", price: 4000, desc: "Certified electrical schematic required for utility connection.", img: "https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=600&q=80" },
    { id: "des-4", type: "service", cat: "Designing", name: "Carport Solar Structure Design", price: 5500, desc: "Architectural blueprint for solar parking shades and canopies.", img: "https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=600&q=80" },
    { id: "des-5", type: "service", cat: "Designing", name: "Hybrid Battery Storage Blueprint", price: 3000, desc: "Wiring design integrating lithium storage banks with backup loads.", img: "https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80" },

    { id: "perm-1", type: "service", cat: "Permitting", name: "Net Metering & Utility Permitting Assistance", price: 5000, desc: "Hassle-free grid connection clearance and official documentation handling.", img: "https://static.wixstatic.com/media/c0e8c5_bd29d31355264fcf91677aea508cba7c~mv2.jpg/v1/fill/w_290,h_194,fp_0.46_0.14,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/R6_K4847.jpg" },
    { id: "perm-2", type: "service", cat: "Permitting", name: "LGU Building Permit Processing", price: 4000, desc: "Local government structural clearance and electrical permit filings.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUgu8nh-IeLbKarOnAMzt5xCPJjk_08otPGLJckm-yCw&s=10" },
    { id: "perm-3", type: "service", cat: "Permitting", name: "HOA Solar Compliance Clearance", price: 1500, desc: "Coordination and document preparation for homeowners associations.", img: "https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=600&q=80" },
    { id: "perm-4", type: "service", cat: "Permitting", name: "Fire Safety Inspection Certificate", price: 2500, desc: "Securing safety approvals for commercial installations.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUgu8nh-IeLbKarOnAMzt5xCPJjk_08otPGLJckm-yCw&s=10" },
    { id: "perm-5", type: "service", cat: "Permitting", name: "Certificate of Final Electrical Inspection (CFEI)", price: 3500, desc: "Official electrical inspection processing for energization.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUgu8nh-IeLbKarOnAMzt5xCPJjk_08otPGLJckm-yCw&s=10" },

    { id: "inst-1", type: "service", cat: "Installation", name: "Turn-Key Residential Solar Installation (3kW)", price: 15000, desc: "Certified solar panel array mounting, wiring, safety grounding, and testing.", img: "https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?auto=format&fit=crop&w=600&q=80" },
    { id: "inst-2", type: "service", cat: "Installation", name: "Large Residential Installation (5kW - 10kW)", price: 25000, desc: "High-capacity multi-string solar installation for larger households.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQkh_5GwBPrNaW7PZN1nVyBgXXamaZdLR8GVFqG_HKs-HpS7h5iTxNOIX8&s=10" },
    { id: "inst-3", type: "service", cat: "Installation", name: "Commercial Rooftop Installation", price: 60000, desc: "Industrial-grade commercial solar array setup and commissioning.", img: "https://images.unsplash.com/photo-1613665813446-82a78c468a1d?auto=format&fit=crop&w=600&q=80" },
    { id: "inst-4", type: "service", cat: "Installation", name: "Off-Grid Battery Storage Setup", price: 12000, desc: "Installation of standalone inverter and deep cycle battery banks.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQkh_5GwBPrNaW7PZN1nVyBgXXamaZdLR8GVFqG_HKs-HpS7h5iTxNOIX8&s=10" },
    { id: "inst-5", type: "service", cat: "Installation", name: "Solar Water Pump System Setup", price: 18000, desc: "Agricultural solar pump installation and controller configuration.", img: "https://images.unsplash.com/photo-1558449028-b53a39d100fc?auto=format&fit=crop&w=600&q=80" },

    { id: "maint-1", type: "service", cat: "Maintenance", name: "Annual System Health Check & Panel Washing", price: 2500, desc: "Regular system health checks and professional solar panel cleaning services.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZ1ZvLYEG5nrU7WkTd3A0njPWQlOYgRGP1_hvMz8-ncA&s=10" },
    { id: "maint-2", type: "service", cat: "Maintenance", name: "Quarterly Preventive Maintenance Contract", price: 8000, desc: "Scheduled quarterly panel washing, inverter tests, and thermal scans.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZ1ZvLYEG5nrU7WkTd3A0njPWQlOYgRGP1_hvMz8-ncA&s=10" },
    { id: "maint-3", type: "service", cat: "Maintenance", name: "Inverter Firmware Update & Calibration", price: 1500, desc: "Diagnostic testing and software optimization for hybrid inverters.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZ1ZvLYEG5nrU7WkTd3A0njPWQlOYgRGP1_hvMz8-ncA&s=10" },
    { id: "maint-4", type: "service", cat: "Maintenance", name: "Battery Health & Capacity Stress Test", price: 3000, desc: "Deep discharge testing and cell balancing for storage banks.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZ1ZvLYEG5nrU7WkTd3A0njPWQlOYgRGP1_hvMz8-ncA&s=10" },
    { id: "maint-5", type: "service", cat: "Maintenance", name: "Post-Typhoon Structural Safety Check", price: 3500, desc: "Urgent post-storm inspection of racking bolts, wiring, and panels.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZ1ZvLYEG5nrU7WkTd3A0njPWQlOYgRGP1_hvMz8-ncA&s=10" },

    { id: "rep-1", type: "service", cat: "Repair", name: "Emergency Inverter Diagnostic & Repair", price: 4000, desc: "Fast component diagnosis, error code resolution, and inverter repairs.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQZ4yTfDyGzec6fqmTs1fWtMs7TGSPYBDex9ETALSZoEA&s=10" },
    { id: "rep-2", type: "service", cat: "Repair", name: "Solar Panel Replacement & Rewiring", price: 5000, desc: "Swapping out damaged or cracked PV panels and re-terminating leads.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQZ4yTfDyGzec6fqmTs1fWtMs7TGSPYBDex9ETALSZoEA&s=10" },
    { id: "rep-3", type: "service", cat: "Repair", name: "Battery Bank Swapping & Disposal", price: 3500, desc: "Safe removal and replacement of expired lead-acid or lithium batteries.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQZ4yTfDyGzec6fqmTs1fWtMs7TGSPYBDex9ETALSZoEA&s=10" },
    { id: "rep-4", type: "service", cat: "Repair", name: "DC/AC Surge Arrester Replacement", price: 2000, desc: "Replacing blown lightning surge arresters after electrical storms.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQZ4yTfDyGzec6fqmTs1fWtMs7TGSPYBDex9ETALSZoEA&s=10" },
    { id: "rep-5", type: "service", cat: "Repair", name: "Roof Leak Sealing (Solar Mounts)", price: 3000, desc: "Waterproofing and resealing roof anchor penetration points.", img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQZ4yTfDyGzec6fqmTs1fWtMs7TGSPYBDex9ETALSZoEA&s=10" },

    { id: "mon-1", type: "service", cat: "Monitoring", name: "Wi-Fi Data Logger Setup & Configuration", price: 2000, desc: "24/7 remote performance tracking setup on smartphone apps.", img: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80" },
    { id: "mon-2", type: "service", cat: "Monitoring", name: "Annual Monitoring Subscription (Cloud)", price: 1800, desc: "1-year secure cloud storage for live generation analytics and logs.", img: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80" },
    { id: "mon-3", type: "service", cat: "Monitoring", name: "Smart Meter Integration & Setup", price: 4500, desc: "Installation of smart export/import power meters for net metering.", img: "https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=600&q=80" },
    { id: "mon-4", type: "service", cat: "Monitoring", name: "Inverter Wi-Fi Module Troubleshooting", price: 1200, desc: "Reconnecting and configuring dropped wireless telemetry links.", img: "https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=600&q=80" },
    { id: "mon-5", type: "service", cat: "Monitoring", name: "Custom Energy Dashboard Configuration", price: 3500, desc: "Setting up custom web dashboards for commercial real-time power tracking.", img: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80" }
];

function wireUserMenu() {
    var menu = document.getElementById("userMenu");
    if (!menu) {
        return;
    }
    var user = getCurrentUser();
    if (!user) {
        return;
    }

    var btn = menu.querySelector(".dropdown-btn");
    if (btn) {
        btn.textContent = user.fname;
    }

    var adminLink = menu.querySelector('a[href="admin"]');
    if (adminLink && user.role !== "admin") {
        adminLink.style.display = "none";
    }

    var logoutLink = menu.querySelector('a[href="SunSonSolarLanding"]');
    if (logoutLink) {
        logoutLink.addEventListener("click", function () {
            logoutAccount();
        });
    }
}

function wireLandingAuthArea() {
    var area = document.getElementById("navAuthArea");
    if (!area) {
        return;
    }
    var user = getCurrentUser();
    if (!user) {
        return; // Not logged in - leave the existing Login / Sign Up markup as-is.
    }

    var btn = area.querySelector(".login-btn");
    if (btn) {
        btn.textContent = user.fname;
    }

    var links = '<a href="' + homeFor(user) + '">Dashboard</a>';
    var menu = ROLE_MENU[roleOf(user)] || [];
    for (var i = 0; i < menu.length; i++) {
        links += '<a href="' + menu[i][0] + '">' + menu[i][1] + '</a>';
    }
    links += '<a href="#" id="landingLogoutLink">Sign Out</a>';

    var content = area.querySelector(".dropdown-content");
    if (content) {
        content.innerHTML = links;
    }

    var logoutLink = document.getElementById("landingLogoutLink");
    if (logoutLink) {
        logoutLink.addEventListener("click", function (e) {
            e.preventDefault();
            logoutAccount();
            window.location.reload();
        });
    }
}


/* ------------------------------------------------------------------
   Roles, departments and starter accounts
   Roles: customer | technician | admin (admin also covers dispatcher / HR).
   Old accounts saved with role "employee" are treated as technicians.
------------------------------------------------------------------ */
var DEPARTMENTS = [
    "Field Operations",
    "Dispatch & Scheduling",
    "Administration & Finance",
    "Human Resources",
    "IT"
];

function roleForDepartment(department) {
    return department === "Field Operations" ? "technician" : "admin";
}

function roleOf(user) {
    var r = user && user.role;
    return r === "employee" ? "technician" : r;
}

var ROLE_HOME = { customer: "customer", technician: "technician", admin: "admin" };

function homeFor(user) {
    return ROLE_HOME[roleOf(user)] || "SunSonSolarLanding";
}

var ROLE_MENU = {
    customer: [
        ["customer", "My Schedule"],
        ["schedule", "Book a Service"],
        ["products", "Products"],
        ["history", "History & Receipts"],
        ["support", "Help & Support"],
        ["notifications", "Notifications"],
        ["account", "Account Settings"]
    ],
    technician: [
        ["technician", "Check In"],
        ["tech-jobs", "My Jobs"],
        ["tech-announcements", "Announcements"],
        ["tech-report", "Report a Problem"],
        ["notifications", "Notifications"],
        ["account", "Account Settings"]
    ],
    admin: [
        ["admin", "Admin Panel"],
        ["timein", "Check In"],
        ["notifications", "Notifications"],
        ["account", "Account Settings"]
    ]
};

// Starter accounts so every role can be tried right away.
// DEMO ONLY - remove these (and hash real passwords) once there is a backend.
(function seedAccounts() {
    var seeds = [
        { fname: "Katherine", lname: "Sinagaraw", mname: "", bdate: "1980-01-01", gender: "Female", email: "kat@sunsonsolar.com", phone: "09123456789", address: "Pasig City", uname: "KittyKat16", password: "K@tSunShine16", role: "admin", department: "Administration & Finance", active: true },
        { fname: "Sol", lname: "Solis", mname: "", bdate: "1990-01-01", gender: "Male", email: "sol@sunsonsolar.com", phone: "09123456790", address: "Pasig City", uname: "admin", password: "admin123", role: "admin", department: "IT", active: true },
        { fname: "Tomas", lname: "Reyes", mname: "", bdate: "1992-04-12", gender: "Male", email: "tomas@sunsonsolar.com", phone: "09171234567", address: "Marikina City", uname: "technician1", password: "Tech@1234", role: "technician", department: "Field Operations", active: true },
        { fname: "Lia", lname: "Santos", mname: "", bdate: "1995-08-30", gender: "Female", email: "lia@sunsonsolar.com", phone: "09181234567", address: "Quezon City", uname: "technician2", password: "Tech@1234", role: "technician", department: "Field Operations", active: true },
        { fname: "Maria", lname: "Cruz", mname: "", bdate: "1988-02-14", gender: "Female", email: "maria.cruz@example.com", phone: "09191234567", address: "123 Sampaguita St., Pasig City", uname: "customer1", password: "Demo@1234", role: "customer", department: "", active: true }
    ];
    var accounts = getAccounts();
    var changed = false;
    for (var i = 0; i < seeds.length; i++) {
        if (!findAccount(seeds[i].uname)) {
            accounts.push(seeds[i]);
            changed = true;
        }
    }
    if (changed) {
        saveAccounts(accounts);
    }
})();
