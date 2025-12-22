import Cocoa

// Arguments: 1: Text, 2: Output Path, 3: Font Size, 4: Width, 5: Alignment (optional)
let args = CommandLine.arguments
guard args.count >= 5 else {
    print("Usage: text_renderer <text> <output_path> <font_size> <width> [alignment]")
    exit(1)
}

let text = args[1]
let outputPath = args[2]
let fontSize = CGFloat(Double(args[3]) ?? 24.0)
let width = CGFloat(Double(args[4]) ?? 384.0)
let alignArg = args.count > 5 ? args[5].lowercased() : "center"

// Setup Font (System Font for Myanmar)
let font = NSFont(name: "Myanmar Sangam MN", size: fontSize) ?? NSFont.systemFont(ofSize: fontSize)

// Setup Paragraph Style
let paragraphStyle = NSMutableParagraphStyle()
if alignArg == "left" {
    paragraphStyle.alignment = .left
} else if alignArg == "right" {
    paragraphStyle.alignment = .right
} else {
    paragraphStyle.alignment = .center
}

// Attributes
let attributes: [NSAttributedString.Key: Any] = [
    .font: font,
    .foregroundColor: NSColor.black,
    .paragraphStyle: paragraphStyle
]

// Create Attributed String
let attributedString = NSAttributedString(string: text, attributes: attributes)

// Calculate Height
let constraintSize = CGSize(width: width, height: .greatestFiniteMagnitude)
let boundingRect = attributedString.boundingRect(with: constraintSize, options: [.usesLineFragmentOrigin, .usesFontLeading])
let height = max(boundingRect.height + 2, fontSize * 1.1) // Tighter spacing

// Create Image Rep explicitly for 1x scale
let pixelWidth = Int(width)
let pixelHeight = Int(height)

guard let rep = NSBitmapImageRep(
    bitmapDataPlanes: nil,
    pixelsWide: pixelWidth,
    pixelsHigh: pixelHeight,
    bitsPerSample: 8,
    samplesPerPixel: 4,
    hasAlpha: true,
    isPlanar: false,
    colorSpaceName: .deviceRGB,
    bytesPerRow: 0,
    bitsPerPixel: 0
) else {
    print("Failed to create bitmap representation")
    exit(1)
}
rep.size = CGSize(width: width, height: height)

NSGraphicsContext.saveGraphicsState()
NSGraphicsContext.current = NSGraphicsContext(bitmapImageRep: rep)

// Draw White Background
NSColor.white.set()
let rect = NSRect(origin: .zero, size: CGSize(width: width, height: height))
rect.fill()

// Draw Text
let textRect = NSRect(
    x: 0, 
    y: (height - boundingRect.height) / 2, 
    width: width,
    height: boundingRect.height
)
attributedString.draw(in: textRect)

NSGraphicsContext.restoreGraphicsState()

// Save to PNG
if let pngData = rep.representation(using: .png, properties: [:]) {
    try pngData.write(to: URL(fileURLWithPath: outputPath))
    print("Image saved to \(outputPath)")
} else {
    print("Failed to create png data")
    exit(1)
}
